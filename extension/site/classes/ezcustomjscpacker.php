<?php


class ezcustomjscPacker extends ezjscPacker {
	
    static function buildStylesheetTag( $cssFiles, $media = 'all', $type = 'text/css', $rel = 'stylesheet', $charset = 'utf-8', $packLevel = 3, $wwwInCacheHash = true )
    {
        $ret = '';
        $packedFiles = ezcustomjscPacker::packFiles( $cssFiles, 'stylesheets/', '_' . $media . '.css', $packLevel, $wwwInCacheHash );
        $http = eZHTTPTool::instance();
        $useFullUrl = ( isset( $http->UseFullUrl ) && $http->UseFullUrl );
        foreach ( $packedFiles as $packedFile )
        {
            // Is this a css file or css content?
            if ( isset( $packedFile[5] ) && strripos( $packedFile, '.css' ) === ( strlen( $packedFile ) -4 ) )
            {
                if ( $useFullUrl )
                {
                    $packedFile = $http->createRedirectUrl( $packedFile, array( 'pre_url' => false ) );
                }
                $ret .= "<link rel=\"$rel\" type=\"$type\" href=\"$packedFile\" media=\"$media\" charset=\"$charset\" />\r\n";
            }
            else
            {
                $ret .= $packedFile ? "<style rel=\"$rel\" type=\"$type\" media=\"$media\">\r\n$packedFile\r\n</style>\r\n" : '';
            }
        }
        return $ret;
    }

    static function packFiles( $fileArray, $subPath = '', $fileExtension = '.js', $packLevel = 2, $wwwInCacheHash = false )
    {
        if ( !$fileArray )
        {
            return array();
        }
        else if ( !is_array( $fileArray ) )
        {
            $fileArray = array( $fileArray );
        }

        $cacheName = '';
        $lastmodified = 0;
        $httpFiles = array();
        $validFiles = array();
        $validWWWFiles = array();
        $bases   = eZTemplateDesignResource::allDesignBases();

        // Only pack files if Packer is enabled and if not set DevelopmentMode is disabled
        $ezjscINI = eZINI::instance('ezjscore.ini');
        if ( $ezjscINI->hasVariable('eZJSCore', 'Packer') )
        {
            $packerIniValue = $ezjscINI->variable('eZJSCore', 'Packer');
            if ( $packerIniValue === 'disabled' )
                $packLevel = 0;
            else if ( is_numeric( $packerIniValue ) )
                $packLevel = (int) $packerIniValue;
        }
        else
        {
            $ini = eZINI::instance();
            if ( $ini->variable('TemplateSettings', 'DevelopmentMode') === 'enabled' )
            {
                $packLevel = 0;
            }
        }

        $packerInfo = array(
            'file_extension' => $fileExtension,
            'pack_level' => $packLevel,
            'sub_path' => $subPath,
            'cache_dir' => self::getCacheDir(),
            'www_dir' => self::getWwwDir(),
        );

        // needed for image includes to work on ezp installs with mixed access methods (virtualhost + url based setup)
        if ( $wwwInCacheHash )
        {
            $cacheName = $packerInfo['www_dir'];
        }

        while( count( $fileArray ) > 0 )
        {
            $file = array_shift( $fileArray );

            // if $file is array, concat it to the file array and continue
            if ( $file && is_array( $file ) )
            {
                $fileArray = array_merge( $file, $fileArray );
                continue;
            }
            else if ( !$file )
            {
                continue;
            }
            // if the file name contains :: it is threated as a custom code genarator
            else if ( strpos( $file, '::' ) !== false )
            {
                $server = self::serverCallHelper( explode( '::', $file )  );
                
                $fileTime = $server->getCacheTime( $packerInfo );

                // generate content straight away if packing is disabled
                if ( $packLevel === 0 )
                {
                   $validWWWFiles[] = $server->call( $fileArray );
                }
                // always generate functions (they modify $fileArray )
                else if ( $fileTime === -1 )
                {
                    $validFiles[] = $server->call( $fileArray );
                }
                else
                {
                    $validFiles[] = $server;
                    $cacheName   .= $file . '_';
                }
                $lastmodified  = max( $lastmodified, $fileTime );
                continue;
            }
            // is it a http url  ?
            else if ( strpos( $file, 'http://' ) === 0 || strpos( $file, 'https://' ) === 0 )
            {
                $httpFiles[] = $file;
                continue;
            }
            // is it a absolute path ?
            else if ( strpos( $file, 'var/' ) === 0 )
            {
                if ( substr( $file, 0, 2 ) === '//' || preg_match( "#^[a-zA-Z0-9]+:#", $file ) )
                    $file = '/';
                else if ( strlen( $file ) > 0 &&  $file[0] !== '/' )
                    $file = '/' . $file;

                eZURI::transformURI( $file, true, 'relative' );
                // get file time and continue if it return false
                $file     = str_replace( '//' . $packerInfo['www_dir'], '', '//' . $file );
                $fileTime = file_exists( $file ) ? filemtime( $file ): false;
                $wwwFile  = $packerInfo['www_dir'] . $file;
            }
            // or is it a relative path
            else
            {
                // allow path to be outside subpath if it starts with '/'
                if ( $file[0] === '/' )
                    $file = ltrim( $file, '/' );
                else
                    $file = $subPath . $file;

                $triedFiles = array();
                $match = eZTemplateDesignResource::fileMatch( $bases, '', $file, $triedFiles );
                if ( $match === false )
                {
                    eZDebug::writeWarning( "Could not find: $file", __METHOD__ );
                    continue;
                }
                $file = htmlspecialchars( $match['path'] );
                $fileTime = file_exists( $file ) ? filemtime( $file ): false;
                $wwwFile  = $packerInfo['www_dir'] . $file;
            }

            if ( $fileTime === false )
            {
                eZDebug::writeWarning( "Could not get modified time of file: $file", __METHOD__ );
                continue;
            }

            // calculate last modified time and store in arrays
            $lastmodified  = max( $lastmodified, $fileTime );
            $validFiles[] = $file;
            $validWWWFiles[] = $wwwFile;
            $cacheName   .= $file . '_';
        }

        // if packing is disabled, return the valid paths / content we have generated
        if ( $packLevel === 0 ) return array_merge( $httpFiles, $validWWWFiles );

        if ( !$validFiles )
        {
            eZDebug::writeWarning( "Could not find any files: " . var_export( $fileArray, true ), __METHOD__ );
            return array();
        }

        // generate cache file name and path
        $cacheName = md5( $cacheName . $packLevel ) . $fileExtension;
        $cachePath = $packerInfo['cache_dir'] . $subPath;

        if ( file_exists( $cachePath . $cacheName ) && 1 == 0)
        {
            // check last modified time and return path to cache file if valid
            if ( $lastmodified <= filemtime( $cachePath . $cacheName ) )
            {
                $httpFiles[] = $packerInfo['www_dir'] . $cachePath . $cacheName;
                return $httpFiles;
            }
        }

        // Merge file content and create new cache file
        $content = '';

        $myINI = eZINI::instance('design.ini');
		$stripus = $myINI->variable('StylesheetSettings', 'StrippedCSSFileList');
//eZDebug::writeNotice($validFiles,'Used Cascading StyleSheet Files');
        foreach( $validFiles as $file)
        {
            // if this is a js / css generator, call to get content
            if ( $file instanceOf ezjscServerRouter )
            {
                $content .= $file->call( $validFiles );
                continue;
            }
            else if ( !$file )
            {
                continue;
            }
            // else, get content of normal file
            $fileContent = file_get_contents( $file );


			if (in_array($file,$stripus)) $fileContent = ezcustomjscPacker::stripcolors($fileContent);

            if ( !trim( $fileContent ) )
            {
                $content .= "/* empty: $file */\r\n";
                continue;
            }

            // we need to fix relative background image paths if this is a css file
            if ( strpos($fileExtension, '.css') !== false )
            {
                $fileContent = ezjscPacker::fixImgPaths( $fileContent, $file );
            }

            $content .= "/* start: $file */\r\n";
            $content .= $fileContent;
            $content .= "\r\n/* end: $file */\r\n\r\n";
        }

        // Pack the file to save bandwidth
        if ( $packLevel > 1 )
        {
            if ( strpos($fileExtension, '.css') !== false )
                $content = ezjscPacker::optimizeCSS( $content, $packLevel );
            else
                $content = ezjscPacker::optimizeScript( $content, $packLevel );
        }

        // save file and return path if sucsessfull
        if( eZFile::create( $cacheName, $cachePath, $content ) )
        {
            $httpFiles[] = $packerInfo['www_dir'] . $cachePath . $cacheName;
            return $httpFiles;
        }

        return array();
    }

	static function stripcolors($fileContent) {
        $myINI = eZINI::instance('design.ini');
		$baduns = $myINI->variable('StylesheetSettings', 'StrippedCSSRuleMatches');
		foreach ($baduns as $stripme) {
			$strip_reg = "/\n\s*".$stripme."[^;]*;/";
			$fileContent = preg_replace($strip_reg, "", $fileContent);
		}
		$badtags = $myINI->variable('StylesheetSettings', 'StrippedCSSTagMatches');
		foreach ($badtags as $stripme) {
			$strip_reg = "/\n[^\{\n]*".$stripme."[^\{]*\{[^\}]*\}/s";
			$fileContent = preg_replace($strip_reg, "", $fileContent);
		}
		return $fileContent;
	}

}

?>