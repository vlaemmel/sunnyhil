<?php
/**
 * File containing the ezcDocumentPdfSvgDriver class
 *
 * @package Document
 * @version 1.2.1
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @access private
 */

/**
 * SVG renderer for PDF driver, useful for manual introspection and test
 * comparisions.
 *
 * ONLY FOR TESTING.
 *
 * @package Document
 * @access private
 * @version 1.2.1
 */
class ezcDocumentPdfSvgDriver extends ezcDocumentPdfDriver
{
    /**
     * Svg Document instance
     *
     * @var DOMDocument
     */
    protected $document;

    /**
     * Node of SVG root element
     *
     * @var DOMElement
     */
    protected $svg;

    /**
     * Root node for page elements
     *
     * @var DOMElement
     */
    protected $pages;

    /**
     * Root node of current page
     *
     * @var DOMElement
     */
    protected $currentpage;

    /**
     * Current inner document offset
     *
     * @var float
     */
    protected $offset = 0;

    /**
     * Next inner document offset after page creation
     *
     * @var float
     */
    protected $nextOffset = 0;

    /**
     * Array with fonts, and their equivalents for bold and italic markup. This
     * array will be extended when loading new fonts, but contains the builtin
     * fonts by default.
     *
     * The fourth value for each font is bold + oblique, the index is the
     * bitwise and combination of the repective combinations. Each font MUST
     * have at least a value for FONT_PLAIN assigned.
     *
     * @var array
     */
    protected $fonts = array(
        'sans-serif' => array(
            self::FONT_PLAIN   => 'Bitstream Vera Sans',
            self::FONT_BOLD    => 'Bitstream Vera Sans',
            self::FONT_OBLIQUE => 'Bitstream Vera Sans',
            3                  => 'Bitstream Vera Sans',
        ),
        'serif' => array(
            self::FONT_PLAIN   => 'Bitstream Vera Serif',
            self::FONT_BOLD    => 'Bitstream Vera Serif',
            self::FONT_OBLIQUE => 'Bitstream Vera Serif',
            3                  => 'Bitstream Vera Serif',
        ),
        'monospace' => array(
            self::FONT_PLAIN   => 'Bitstream Vera Sans Mono',
            self::FONT_BOLD    => 'Bitstream Vera Sans Mono',
            self::FONT_OBLIQUE => 'Bitstream Vera Sans Mono',
            3                  => 'Bitstream Vera Sans Mono',
        ),
        'Symbol' => array(
            self::FONT_PLAIN   => 'Symbol',
        ),
        'ZapfDingbats' => array(
            self::FONT_PLAIN   => 'ZapfDingbats',
        ),
    );

    /**
     * Name and style of default font / currently used font
     *
     * @var array
     */
    protected $currentFont = array(
        'name'  => 'sans-serif',
        'style' => self::FONT_PLAIN,
        'size'  => 28.5,
        'font'  => null,
    );

    /**
     * Construct driver
     *
     * Creates a new document instance maintaining all document context.
     *
     * @return void
     */
    public function __construct()
    {
        $this->document = new DOMDocument( '1.0' );
        $this->document->formatOutput = true;

        $this->svg = $this->document->createElementNS( 'http://www.w3.org/2000/svg', 'svg' );
        $this->svg = $this->document->appendChild( $this->svg );

        $this->svg->setAttribute( 'version', '1.2' );
        $this->svg->setAttribute( 'streamable', 'true' );

        $this->pages = $this->document->createElement( 'g' );
        $this->pages = $this->svg->appendChild( $this->pages );
        $this->pages->setAttribute( 'id', 'pages' );
    }

    /**
     * Create a new page
     *
     * Create a new page in the PDF document with the given width and height.
     *
     * @param float $width
     * @param float $height
     * @return void
     */
    public function createPage( $width, $height )
    {
        $this->offset      = $this->nextOffset;
        $this->nextOffset += $width + 10;

        $this->currentPage = $this->document->createElement( 'g' );
        $this->currentPage = $this->pages->appendChild( $this->currentPage );

        // Render a containing box visually representing a page in the box
        $page = $this->document->createElement( 'rect' );
        $page = $this->currentPage->appendChild( $page );
        $page->setAttribute( 'x', $this->offset . 'mm' );
        $page->setAttribute( 'y', '0mm' );
        $page->setAttribute( 'width', $width . 'mm' );
        $page->setAttribute( 'height', $height . 'mm' );
        $page->setAttribute( 'style', 'fill: #ffffff; stroke: #000000; stroke-width: 1px; fill-opacity: 1; stroke-opacity: 1;' );
    }

    /**
     * Set text formatting option
     *
     * Set a text formatting option. The names of the options are the same used
     * in the PCSS files and need to be translated by the driver to the proper
     * backend calls.
     *
     *
     * @param string $type
     * @param mixed $value
     * @return void
     */
    public function setTextFormatting( $type, $value )
    {
        switch ( $type )
        {
            case 'font-style':
                if ( ( $value === 'oblique' ) ||
                     ( $value === 'italic' ) )
                {
                    $this->currentFont['style'] |= self::FONT_OBLIQUE;
                }
                else
                {
                    $this->currentFont['style'] &= ~self::FONT_OBLIQUE;
                }
                break;

            case 'font-weight':
                if ( ( $value === 'bold' ) ||
                     ( $value === 'bolder' ) )
                {
                    $this->currentFont['style'] |= self::FONT_BOLD;
                }
                else
                {
                    $this->currentFont['style'] &= ~self::FONT_BOLD;
                }
                break;

            case 'font-family':
                $this->currentFont['name'] = $value;
                break;

            case 'font-size':
                $this->currentFont['size'] = ezcDocumentPdfMeasure::create( $value )->get( 'pt' );
                break;

            default:
                // @TODO: Error reporting.
        }
    }

    /**
     * Calculate the rendered width of the current word
     *
     * Calculate the width of the passed word, using the currently set text
     * formatting options.
     *
     * @param string $word
     * @return float
     */
    public function calculateWordWidth( $word )
    {
        return ezcDocumentPdfMeasure::create(
            ( $this->currentFont['size'] * iconv_strlen( $word, 'UTF-8' ) * .43 ) . 'pt'
        )->get();
    }

    /**
     * Get current line height
     *
     * Return the current line height in millimeter based on the current font
     * and text rendering settings.
     *
     * @return float
     */
    public function getCurrentLineHeight()
    {
        return ezcDocumentPdfMeasure::create( $this->currentFont['size'] . 'pt' )->get();
    }

    /**
     * Draw word at given position
     *
     * Draw the given word at the given position using the currently set text
     * formatting options.
     *
     * @param float $x
     * @param float $y
     * @param string $word
     * @return void
     */
    public function drawWord( $x, $y, $word )
    {
        $textNode = $this->document->createElement( 'text', htmlspecialchars( $word,  ENT_QUOTES, 'UTF-8' ) );
        $textNode->setAttribute( 'x', sprintf( '%.4Fmm', $x + $this->offset ) );
        $textNode->setAttribute( 'y', sprintf( '%.4Fmm', $y ) );
        $textNode->setAttribute(
            'style',
            sprintf(
                'font-size: %.2Fpt; font-family: %s; font-style: %s; font-weight: %s; stroke: none;',
                $this->currentFont['size'],
                $this->fonts[$this->currentFont['name']][self::FONT_PLAIN],
                ( $this->currentFont['style'] & self::FONT_OBLIQUE ) ? 'oblique' : 'normal',
                ( $this->currentFont['style'] & self::FONT_BOLD )    ? 'bold'    : 'normal'
            )
        );
        $this->currentPage->appendChild( $textNode );
    }

    /**
     * Draw image
     *
     * Draw image at the defined position. The first parameter is the
     * (absolute) path to the image file, and the second defines the type of
     * the image. If the driver cannot handle this aprticular image type, it
     * should throw an exception.
     *
     * The further parameters define the location where the image should be
     * rendered and the dimensions of the image in the rendered output. The
     * dimensions do not neccesarily match the real image dimensions, and might
     * require some kind of scaling inside the driver depending on the used
     * backend.
     *
     * @param string $file
     * @param string $type
     * @param float $x
     * @param float $y
     * @param float $width
     * @param float $height
     * @return void
     */
    public function drawImage( $file, $type, $x, $y, $width, $height )
    {
        $image = $this->document->createElement( 'image' );

        $image->setAttribute( 'x', sprintf( '%.4Fmm', $x + $this->offset ) );
        $image->setAttribute( 'y', sprintf( '%.4Fmm', $y ) );
        $image->setAttribute( 'width', sprintf( '%.4Fmm', $width ) );
        $image->setAttribute( 'height', sprintf( '%.4Fmm', $height ) );
        $image->setAttributeNS(
            'http://www.w3.org/1999/xlink',
            'xlink:href',
            sprintf( 'data:%s;base64,%s',
                $type,
                base64_encode( file_get_contents( $file ) )
            )
        );

        $this->currentPage->appendChild( $image );
    }

    /**
     * Generate and return PDF
     *
     * Return the generated binary PDF content as a string.
     *
     * @return string
     */
    public function save()
    {
        return $this->document->saveXml();
    }
}
?>
