<?php
//
// Definition of ezjscAjaxContent class
//
// Created on: <5-Aug-2007 00:00:00 ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ JSCore extension for eZ Publish
// SOFTWARE RELEASE: 1.0-1
// COPYRIGHT NOTICE: Copyright (C) 2009 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

// Simplifying and encoding content objects / nodes to json
// using the php json extension included in php 5.2


class ezjscAjaxContent
{
    /**
     * Constructor
     */
    protected function __construct()
    {
    }

    /**
     * Clone
     */
    protected function __clone()
    {
    }

    /**
     * Gets the first most prefered response type as defined by http_accept
     * uses post and get parameter if present, if not falls back to the one
     * defined in http header. First parameter lets you define fallback value
     * if none of the alternatives in second parameter is found. Second parameter
     * lets you limit the allowes types with a alias hash. xhtml, json, xml and
     * text are default allowed types.
     *
     * @param string $default
     * @param array $aliasList
     * @return string
     */
    public static function getHttpAccept( $default = 'xhtml', $aliasList = array( 'html' => 'xhtml', 'json' => 'json', 'javascript' => 'json', 'xml' => 'xml', 'text' => 'text' ) )
    {
        if ( isset($_POST['http_accept']) )
            $acceptList = explode( ',', $_POST['http_accept'] );
        else if ( isset($_POST['HTTP_ACCEPT']) )
            $acceptList = explode( ',', $_POST['HTTP_ACCEPT'] );
        else if ( isset($_GET['http_accept']) )
            $acceptList = explode( ',', $_GET['http_accept'] );
        else if ( isset($_GET['HTTP_ACCEPT']) )
            $acceptList = explode( ',', $_GET['HTTP_ACCEPT'] );
        else
            $acceptList = explode( ',', $_SERVER['HTTP_ACCEPT'] );

        foreach( $acceptList as $accept )
        {
            foreach( $aliasList as $alias => $returnType )
            {
                if ( strpos( $accept, $alias ) !== false )
                {
                    $default = $returnType;
                    break 2;
                }
            }
        }
        return $default;
    }

    /**
     * Encodes the content based on http accept values, more on
     * this on the getHttpAccept function.
     * Will simply implode the return value if array and not xml or 
     * json is prefered return type.
     *
     * @param mixed $ret
     * @param string $type
     * @return string
     */
    public static function autoEncode( $ret, $type = null )
    {
        if ( $type === null )
            $type = self::getHttpAccept( );

        if ( $type === 'xml' )
            return self::xmlEncode( $ret );
        else if ( $type === 'json' )
            return json_encode( $ret );
        else
            return self::textEncode( $ret );
    }

    /**
     * Encodes mixed value to string or comma seperated list of strings
     *
     * @param mixed $mix
     * @return string
     */
    public static function textEncode( $mix )
    {
        if ( is_array( $mix ) )
            return implode(',', array_map( array('ezjscAjaxContent', 'textEncode'), array_filter( $mix ) ) );

        return $mix;
    }

    /**
     * Function for encoding content object(s) or node(s) to simplified
     * json objects, xml or array hash
     * 
     * @param mixed $obj
     * @param array $params
     * @param string $type
     * @return mixed
     */
    public static function nodeEncode( $obj, $params = array(), $type = 'json' )
    {
        if ( is_array( $obj ) )
        {
            $ret = array();
            foreach ( $obj as $ob )
            {
                $ret[] = self::simplify( $ob, $params );
            }
        }
        else
        {
            $ret = self::simplify( $obj, $params );
        }

        if ( $type === 'xml' )
            return self::xmlEncode( $ret );
        else if ( $type === 'json' )
            return json_encode( $ret );
        else
            return $ret;
    }

    /**
     * Function for simplifying a content object or node
     *
     * @param mixed $obj
     * @param array $params
     * @return array
     */
    public static function simplify( $obj, $params = array() )
    {
        if ( !$obj )
        {
            return array();
        }
        else if ( $obj instanceof eZContentObject)
        {
            $node          = $obj->attribute( 'main_node' );
            $contentObject = $obj;
        }
        else if ( $obj instanceof eZContentObjectTreeNode || $obj instanceof eZFindResultNode ) 
        {
            $node          = $obj;
            $contentObject = $obj->attribute( 'object' );
        }
        else if( isset( $params['fetchNodeFunction'] ) && method_exists( $obj, $params['fetchNodeFunction'] ) )
        {
            // You can supply fetchNodeFunction parameter to be able to support other node related classes 
            $node = call_user_func( array( $obj, $params['fetchNodeFunction'] ) );
            if ( !$node instanceof eZContentObjectTreeNode )
            {
                return '';
            }
            $contentObject = $node->attribute( 'object' );
        }
        else if ( is_array( $obj ) )
        {
            return $obj; // Array is returned as is
        }
        else
        {
            return ''; // Other passed objects are not supported
        }

        $ini = eZINI::instance( 'site.ini' );
        $params = array_merge( array(
                            'dataMap' => array(), // collection of identifiers you want to load, load all with array('all')
                            'fetchPath' => false, // fetch node path
                            'fetchChildrenCount' => false,
                            'dataMapType' => array(), //if you want to filter datamap by type
                            'loadImages' => false,
                            'imagePreGenerateSizes' => array('small') //Pre generated images, loading all can be quite time consuming
        ), $params );

        if ( !isset( $params['imageSizes'] ) )// list of available image sizes
        {
            $imageIni = eZINI::instance( 'image.ini' );
            $params['imageSizes'] = $imageIni->variable( 'AliasSettings', 'AliasList' );
        }

        if ( $params['imageSizes'] === null || !isset( $params['imageSizes'][0] ) )
            $params['imageSizes'] = array();
            
        if (  !isset( $params['imageDataTypes'] ) )
            $params['imageDataTypes'] = $ini->variable( 'ImageDataTypeSettings', 'AvailableImageDataTypes' );

        $ret                     = array();
        $attrtibuteArray         = array();
        $ret['name']             = $contentObject->attribute( 'name' );
        $ret['contentobject_id'] = $ret['id'] = (int) $contentObject->attribute( 'id' );
        $ret['main_node_id']     = (int)$contentObject->attribute( 'main_node_id' );
        $ret['modified']         = $contentObject->attribute( 'modified' );
        $ret['published']        = $contentObject->attribute( 'published' );
        $ret['section_id']       = (int) $contentObject->attribute( 'section_id' );
        $ret['current_language'] = $contentObject->attribute( 'current_language' );
        $ret['owner_id']         = (int) $contentObject->attribute( 'owner_id' );
        $ret['class_id']         = (int) $contentObject->attribute( 'contentclass_id' );
        $ret['class_name']       = $contentObject->attribute( 'class_name' );

        if ( $node )
        {
            // optimization for eZ Publish 4.1 (avoid fetching class)
            if ( $node->hasAttribute( 'is_container' ) )
            {
                $ret['class_identifier'] = $node->attribute( 'class_identifier' );
                $ret['is_container']     = (int) $node->attribute( 'is_container' );
            }
            else
            {
                $class                   = $contentObject->attribute( 'content_class' );
                $ret['class_identifier'] = $class->attribute( 'identifier' );
                $ret['is_container']     = (int) $class->attribute( 'is_container' );
            }
            
            $ret['node_id']        = (int) $node->attribute( 'node_id' );
            $ret['parent_node_id'] = (int) $node->attribute( 'parent_node_id' );
            $ret['url_alias']      = $node->attribute( 'url_alias' );
            $ret['depth']          = (int) $node->attribute( 'depth' );

            if ( $params['fetchPath'] )
            {
                $ret['path'] = array();
                foreach ( $node->attribute( 'path' ) as $n )
                {
                    $ret['path'][] = self::simplify( $n );
                }
            }
            else
            {
                $ret['path'] = false;
            }

            if ( $params['fetchChildrenCount'] )
            {
                $ret['children_count'] = $ret['is_container'] ? (int) $node->attribute( 'children_count' ) : 0;
            }
            else
            {
                $ret['children_count'] = false;
            }
        }
        else
        {
            $class                   = $contentObject->attribute( 'content_class' );
            $ret['class_identifier'] = $class->attribute( 'identifier' );
            $ret['is_container']     = (int) $class->attribute( 'is_container' );
        }

        $ret['image_attributes'] = array();

        if ( is_array( $params['dataMap'] ) && is_array(  $params['dataMapType'] ) )
        {
            $dataMap = $contentObject->attribute( 'data_map' );
            foreach( $dataMap as $key => $atr )
            {
                $dataTypeString = $atr->attribute( 'data_type_string' );
                //if ( in_array( $dataTypeString, $params['imageDataTypes'], true) !== false )

                if ( !in_array( 'all' ,$params['dataMap'], true )
                   && !in_array( $key ,$params['dataMap'], true )
                   && !in_array( $dataTypeString, $params['dataMapType'], true )
                   && !( $params['loadImages'] && in_array( $dataTypeString, $params['imageDataTypes'], true ) )
                   ) continue;
                $attrtibuteArray[ $key ]['id']         = $atr->attribute( 'id' );
                $attrtibuteArray[ $key ]['type']       = $dataTypeString;
                $attrtibuteArray[ $key ]['identifier'] = $key;
                $attrtibuteArray[ $key ]['content']    = $atr->toString();

                // images
                if ( in_array( $dataTypeString, $params['imageDataTypes'], true) !== false )
                {
                    $content    = $atr->attribute( 'content' );
                    $imageArray = array();
                    if ( $content != null )
                    {
                        foreach( $params['imageSizes'] as $size )
                        {
                            $imageArray[ $size ] = false;
                            if ( in_array( $size, $params['imagePreGenerateSizes'] )
                                && $content->hasAttribute( $size ) )
                                $imageArray[ $size ] = $content->attribute( $size );
                        }
                        $ret['image_attributes'][] = $key;
                    }

                    $imageArray['original']             = array( 'url' => $attrtibuteArray[ $key ]['content'] );
                    $attrtibuteArray[ $key ]['content'] = $imageArray;
                }
            }
        }
        $ret['data_map'] = $attrtibuteArray;
        return $ret;
    }

    /**
     * Encodes simple multilevel array and hash values to valid xml string
     * 
     * @param mixed $hash
     * @param string $childName
     * @return string
    */
    public static function xmlEncode( $hash, $childName = 'child' )
    {
        $xml = new XmlWriter();
        $xml->openMemory();
        $xml->startDocument('1.0', 'UTF-8');
        $xml->startElement('root');
        
        self::xmlWrite( $xml, $hash, $childName );
        
        $xml->endElement();
        return $xml->outputMemory( true );
    
    }

    /**
     * Recursive xmlWriter function called by xmlEncode
     * 
     * @param XMLWriter $xml
     * @param mixed $hash
     * @param string $childName
    */
    protected static function xmlWrite( XMLWriter $xml, $hash, $childName = 'child' )
    {
        foreach( $hash as $key => $value )
        {
            if( is_array( $value ) )
            {
               if ( is_numeric( $key ) )
                   $xml->startElement( $childName );
               else
                   $xml->startElement( $key );
                self::xmlWrite( $xml, $value );
                $xml->endElement();
                continue;
            }
            if ( is_numeric( $key ) )
            {
                $xml->writeElement( $childName, $value );
            }
            else
            {
                $xml->writeElement( $key, $value );
            }
        }
    }
}

?>