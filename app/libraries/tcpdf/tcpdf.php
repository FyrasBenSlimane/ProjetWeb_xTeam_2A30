<?php
/**
 * TCPDF Wrapper for the user export functionality
 * This is a simplified version of TCPDF for basic PDF generation
 */

class TCPDF {
    // Constants used in the code
    const PDF_PAGE_ORIENTATION = 'P';
    const PDF_UNIT = 'mm';
    const PDF_PAGE_FORMAT = 'A4';
    const PDF_CREATOR = 'TCPDF';
    const PDF_FONT_NAME_MAIN = 'helvetica';
    const PDF_FONT_SIZE_MAIN = 10;
    const PDF_FONT_NAME_DATA = 'helvetica';
    const PDF_FONT_SIZE_DATA = 8;
    const PDF_FONT_MONOSPACED = 'courier';
    const PDF_MARGIN_LEFT = 15;
    const PDF_MARGIN_TOP = 27;
    const PDF_MARGIN_RIGHT = 15;
    const PDF_MARGIN_HEADER = 5;
    const PDF_MARGIN_FOOTER = 10;
    const PDF_MARGIN_BOTTOM = 25;
    const PDF_IMAGE_SCALE_RATIO = 1.25;

    private $title;
    private $author;
    private $subject;
    private $creator;
    private $headerTitle;
    private $headerString;
    private $orientation;
    private $unit;
    private $format;
    private $unicode;
    private $encoding;
    private $content = '';
    private $fileName;

    /**
     * Constructor
     * 
     * @param string $orientation Page orientation (P=portrait, L=landscape)
     * @param string $unit Unit of measure (pt, mm, cm, in)
     * @param string $format Page format (A4, Letter, etc)
     * @param bool $unicode True if Unicode support is required
     * @param string $encoding Charset encoding
     * @param bool $diskcache If true, reduce memory usage by caching to disk
     */
    public function __construct($orientation = 'P', $unit = 'mm', $format = 'A4', $unicode = true, $encoding = 'UTF-8', $diskcache = false) {
        $this->orientation = $orientation;
        $this->unit = $unit;
        $this->format = $format;
        $this->unicode = $unicode;
        $this->encoding = $encoding;
    }

    /**
     * Set document information
     * 
     * @param string $creator Document creator
     */
    public function SetCreator($creator) {
        $this->creator = $creator;
    }

    /**
     * Set document author
     * 
     * @param string $author Document author
     */
    public function SetAuthor($author) {
        $this->author = $author;
    }

    /**
     * Set document title
     * 
     * @param string $title Document title
     */
    public function SetTitle($title) {
        $this->title = $title;
    }

    /**
     * Set document subject
     * 
     * @param string $subject Document subject
     */
    public function SetSubject($subject) {
        $this->subject = $subject;
    }

    /**
     * Set header data
     * 
     * @param string $ln Logo name/path
     * @param int $lw Logo width
     * @param string $ht Header title
     * @param string $hs Header string
     */
    public function SetHeaderData($ln = '', $lw = 0, $ht = '', $hs = '') {
        $this->headerTitle = $ht;
        $this->headerString = $hs;
    }

    /**
     * Set header font
     * 
     * @param array $font Font array
     */
    public function setHeaderFont($font) {
        // This would set header font in a real implementation
    }

    /**
     * Set footer font
     * 
     * @param array $font Font array
     */
    public function setFooterFont($font) {
        // This would set footer font in a real implementation
    }

    /**
     * Set default monospaced font
     * 
     * @param string $font Font name
     */
    public function SetDefaultMonospacedFont($font) {
        // This would set default monospaced font in a real implementation
    }

    /**
     * Set margins
     * 
     * @param float $left Left margin
     * @param float $top Top margin
     * @param float $right Right margin
     */
    public function SetMargins($left, $top, $right) {
        // This would set margins in a real implementation
    }

    /**
     * Set header margin
     * 
     * @param float $margin Header margin
     */
    public function SetHeaderMargin($margin) {
        // This would set header margin in a real implementation
    }

    /**
     * Set footer margin
     * 
     * @param float $margin Footer margin
     */
    public function SetFooterMargin($margin) {
        // This would set footer margin in a real implementation
    }

    /**
     * Set auto page breaks
     * 
     * @param bool $auto Auto page break
     * @param float $margin Bottom margin
     */
    public function SetAutoPageBreak($auto, $margin) {
        // This would set auto page break in a real implementation
    }

    /**
     * Set image scale factor
     * 
     * @param float $scale Image scale factor
     */
    public function setImageScale($scale) {
        // This would set image scale factor in a real implementation
    }

    /**
     * Add a page
     */
    public function AddPage() {
        // This would add a page in a real implementation
        $this->content .= "<div style='page-break-before: always;'></div>\n";
    }

    /**
     * Set font
     * 
     * @param string $family Font family
     * @param string $style Font style
     * @param float $size Font size
     */
    public function SetFont($family, $style = '', $size = null) {
        // This would set font in a real implementation
    }

    /**
     * Set Y position
     * 
     * @param float $y Y position
     */
    public function SetY($y) {
        // This would set Y position in a real implementation
    }

    /**
     * Add a cell
     * 
     * @param float $w Width
     * @param float $h Height
     * @param string $txt Text
     * @param mixed $border Border
     * @param int $ln Line break
     * @param string $align Alignment
     */
    public function Cell($w, $h, $txt, $border = 0, $ln = 0, $align = '') {
        // This would add a cell in a real implementation
        $this->content .= "<div>$txt</div>\n";
    }

    /**
     * Add a line break
     * 
     * @param float $h Height
     */
    public function Ln($h = null) {
        // This would add a line break in a real implementation
        $this->content .= "<br>\n";
    }

    /**
     * Write HTML
     * 
     * @param string $html HTML content
     * @param bool $ln Line break
     */
    public function writeHTML($html, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '') {
        // This would write HTML in a real implementation
        $this->content .= $html;
    }

    /**
     * Output PDF
     * 
     * @param string $name File name
     * @param string $dest Destination
     */
    public function Output($name = 'doc.pdf', $dest = 'I') {
        $this->fileName = $name;

        // In a real implementation, this would generate and output the PDF
        // For this example, we'll just generate an HTML preview
        header('Content-Type: text/html; charset=utf-8');
        
        echo '<!DOCTYPE html>
        <html>
        <head>
            <title>' . htmlspecialchars($this->title) . '</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #ccc; }
                .footer { text-align: center; margin-top: 20px; padding-top: 10px; border-top: 1px solid #ccc; font-size: 0.8em; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                th { background-color: #f1f5f9; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                .page-break { page-break-before: always; }
                @media print {
                    .no-print { display: none; }
                }
            </style>
        </head>
        <body>
            <div class="no-print" style="background-color: #ffeb3b; padding: 10px; margin-bottom: 20px; border-radius: 5px;">
                <strong>PDF Generation Simulation:</strong> In a real implementation, this would generate a PDF. 
                This is a simplified HTML preview of what the PDF would look like.
                <a href="' . URLROOT . '/dashboard/user_management" style="display: block; margin-top: 10px;">Back to User Management</a>
            </div>
            <div class="header">
                <h2>' . htmlspecialchars($this->headerTitle) . '</h2>
                <p>' . htmlspecialchars($this->headerString) . '</p>
            </div>
            <div class="content">' . $this->content . '</div>
            <div class="footer">
                <p>Generated by ' . htmlspecialchars($this->author) . ' | File: ' . htmlspecialchars($this->fileName) . '</p>
            </div>
        </body>
        </html>';
        
        exit;
    }
}

// Define constants
define('PDF_PAGE_ORIENTATION', TCPDF::PDF_PAGE_ORIENTATION);
define('PDF_UNIT', TCPDF::PDF_UNIT);
define('PDF_PAGE_FORMAT', TCPDF::PDF_PAGE_FORMAT);
define('PDF_CREATOR', TCPDF::PDF_CREATOR);
define('PDF_FONT_NAME_MAIN', TCPDF::PDF_FONT_NAME_MAIN);
define('PDF_FONT_SIZE_MAIN', TCPDF::PDF_FONT_SIZE_MAIN);
define('PDF_FONT_NAME_DATA', TCPDF::PDF_FONT_NAME_DATA);
define('PDF_FONT_SIZE_DATA', TCPDF::PDF_FONT_SIZE_DATA);
define('PDF_FONT_MONOSPACED', TCPDF::PDF_FONT_MONOSPACED);
define('PDF_MARGIN_LEFT', TCPDF::PDF_MARGIN_LEFT);
define('PDF_MARGIN_TOP', TCPDF::PDF_MARGIN_TOP);
define('PDF_MARGIN_RIGHT', TCPDF::PDF_MARGIN_RIGHT);
define('PDF_MARGIN_HEADER', TCPDF::PDF_MARGIN_HEADER);
define('PDF_MARGIN_FOOTER', TCPDF::PDF_MARGIN_FOOTER);
define('PDF_MARGIN_BOTTOM', TCPDF::PDF_MARGIN_BOTTOM);
define('PDF_IMAGE_SCALE_RATIO', TCPDF::PDF_IMAGE_SCALE_RATIO); 