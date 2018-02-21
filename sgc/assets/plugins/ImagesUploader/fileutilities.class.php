<?php

class FileUtilities
{
    /**
     * Generates a file name.
     * @param $path
     * @param $filename
     * @return string
     */
    public static function generateFilename($path, $filename)
    {
        $file_bits = explode(".", $filename);

        $name = implode(".", explode(".", $filename, -1));
        $extension = $file_bits[sizeof($file_bits) - 1];

        $name = $tmp_name = self::slug($name);

        $i = 0;
        while (file_exists($path . $tmp_name . "." . $extension)) {
            $tmp_name = $name . "-" . $i;
            $i++;
        }

        return $tmp_name . "." . $extension;
    }

    /**
     * Applies a watermark to an image stored
     * Note: watermark file must be a PNG
     * @param $img_file
     * @param $watermark_file
     * @param int $wm_width_prctg
     * @param int $wm_height_prctg
     * @return bool
     */
    public static function applyWatermark($img_file, $watermark_file, $wm_width_prctg = 80, $wm_height_prctg = 80)
    {
        $info = getimagesize($img_file);

        // loading image to memory according to type
        switch ($info[2]) {
            case IMAGETYPE_JPEG:
                $img = imagecreatefromjpeg($img_file);
                break;
            case IMAGETYPE_GIF:
                $img = imagecreatefromgif($img_file);
                break;
            case IMAGETYPE_PNG:
                $img = imagecreatefrompng($img_file);
                break;
            default:
                return false;
                exit;
        }
        // image dimensions
        $img_w = imagesx($img);
        $img_h = imagesy($img);


        /*
            watermar handling
        */
        $watermark = imagecreatefrompng($watermark_file);
        $wtrmrk_max_w = round($img_w * $wm_width_prctg / 100);
        $wtrmrk_max_h = round($img_h * $wm_height_prctg / 100);

        // create temporary watermark file and resize it to fit original image
        $wtmrk_path_bits = pathinfo($watermark_file);
        $tmp_watermark_file = $wtmrk_path_bits['dirname'] . '/' . md5(microtime()) . '.' . $wtmrk_path_bits['extension'];
        copy($watermark_file, $tmp_watermark_file);
        self::smartResizeImage($tmp_watermark_file, null, $wtrmrk_max_w, $wtrmrk_max_h);

        // refresh watermark
        $watermark = imagecreatefrompng($tmp_watermark_file);
        imagealphablending($watermark, false);
        imagesavealpha($watermark, true);

        // watermark dimension
        $wtrmrk_w = imagesx($watermark);
        $wtrmrk_h = imagesy($watermark);

        // center watermark
        $dst_x = ($img_w / 2) - ($wtrmrk_w / 2);
        $dst_y = ($img_h / 2) - ($wtrmrk_h / 2);

        // copy image
        imagecopy($img, $watermark, $dst_x, $dst_y, 0, 0, $wtrmrk_w, $wtrmrk_h);
        imagejpeg($img, $img_file, 100);
        imagedestroy($img);
        imagedestroy($watermark);

        // delete temporary watermark
        unlink($tmp_watermark_file);
    }

    /**
     * easy image resize function
     * @param  $file - file name to resize
     * @param  $string - The image data, as a string
     * @param  $max_width - new image max width
     * @param  $max_height - new image max height
     * @param  $proportional - keep image proportional, default is no
     * @param  $quality - enter 1-100 (100 is best quality) default is 100
     * @return boolean|resource
     */
    public static function smartResizeImage(
        $file,
        $string = NULL,
        $max_width = 0,
        $max_height = 0,
        $proportional = true,
        $quality = 100
    )
    {

        if ($max_height <= 0 && $max_width <= 0) return false;
        if ($file === NULL && $string === NULL) return false;

        // setting defaults and meta
        $info = getimagesize($file);
        $original_file_size = filesize($file);
        $image = '';
        list($final_width, $final_height) = $info;
        list($old_width, $old_height) = $info;
        $crop_height = $crop_width = 0;

        // resize only if width or height exceeds maximum allowed
        if ($old_width > $max_width || $old_height > $max_height) {
            // calculating proportionality
            if ($proportional) {
                if ($max_width == 0)
                    $factor = $max_height / $old_height;
                else if ($max_height == 0)
                    $factor = $max_width / $old_width;
                else
                    $factor = min($max_width / $old_width, $max_height / $old_height);

                $final_width = round($old_width * $factor);
                $final_height = round($old_height * $factor);
            } else {
                $final_width = ($max_width <= 0) ? $old_width : $max_width;
                $final_height = ($max_height <= 0) ? $old_height : $max_height;

                $widthX = $old_width / $max_width;
                $heightX = $old_height / $max_height;

                $x = min($widthX, $heightX);
                $crop_width = ($old_width - $max_width * $x) / 2;
                $crop_height = ($old_height - $max_height * $x) / 2;
            }
        }

        // loading image to memory according to type
        switch ($info[2]) {
            case IMAGETYPE_JPEG:
                $image = $file !== NULL ? imagecreatefromjpeg($file) : imagecreatefromstring($string);
                break;
            case IMAGETYPE_GIF:
                $image = $file !== NULL ? imagecreatefromgif($file) : imagecreatefromstring($string);
                break;
            case IMAGETYPE_PNG:
                $image = $file !== NULL ? imagecreatefrompng($file) : imagecreatefromstring($string);
                break;
            default:
                return false;
        }

        // this is the resizing/resampling/transparency-preserving magic
        $image_resized = imagecreatetruecolor($final_width, $final_height);
        if (($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG)) {
            $transparency = imagecolortransparent($image);
            $palletsize = imagecolorstotal($image);

            if ($transparency >= 0 && $transparency < $palletsize) {
                $transparent_color = imagecolorsforindex($image, $transparency);
                $transparency = imagecolorallocate($image_resized, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
                imagefill($image_resized, 0, 0, $transparency);
                imagecolortransparent($image_resized, $transparency);
            } else if ($info[2] == IMAGETYPE_PNG) {
                imagealphablending($image_resized, false);
                $color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
                imagefill($image_resized, 0, 0, $color);
                imagesavealpha($image_resized, true);
            }
        }

        imagecopyresampled($image_resized, $image, 0, 0, $crop_width, $crop_height, $final_width, $final_height, $old_width - 2 * $crop_width, $old_height - 2 * $crop_height);
        // writing image according to type to the output destination and image quality
        switch ($info[2]) {
            case IMAGETYPE_GIF:
                imagegif($image_resized, $file);
                break;
            case IMAGETYPE_JPEG:
                imagejpeg($image_resized, $file, $quality);
                break;
            case IMAGETYPE_PNG:
                $quality = 9 - (int)((0.9 * $quality) / 10);
                //echo $quality;
                imagepng($image_resized, $file, $quality);
                break;
            default:
                return false;
        }

        return true;
    }


    /**
     * Transliterate a UTF-8 value to ASCII.
     *
     * @param  string $value
     * @return string
     */
    public static function ascii($value)
    {
        foreach (static::charsArray() as $key => $val) {
            $value = str_replace($val, $key, $value);
        }

        return preg_replace('/[^\x20-\x7E]/u', '', $value);
    }


    /**
     * Generate a URL friendly "slug" from a given string.
     *
     * @param  string $title
     * @param  string $separator
     * @return string
     */
    public static function slug($title, $separator = '-')
    {
        $title = static::ascii($title);

        // Convert all dashes/underscores into separator
        $flip = $separator == '-' ? '_' : '-';

        $title = preg_replace('![' . preg_quote($flip) . ']+!u', $separator, $title);

        // Remove all characters that are not the separator, letters, numbers, or whitespace.
        $title = preg_replace('![^' . preg_quote($separator) . '\pL\pN\s]+!u', '', mb_strtolower($title));

        // Replace all separator characters and whitespace by a single separator
        $title = preg_replace('![' . preg_quote($separator) . '\s]+!u', $separator, $title);

        return trim($title, $separator);
    }


    /**
     * Returns the replacements for the ascii method.
     *
     * Note: Adapted from Stringy\Stringy.
     *
     * @see https://github.com/danielstjules/Stringy/blob/2.3.1/LICENSE.txt
     *
     * @return array
     */
    protected static function charsArray()
    {
        static $charsArray;

        if (isset($charsArray)) {
            return $charsArray;
        }

        return $charsArray = [
            '0' => ['°', '₀', '۰'],
            '1' => ['¹', '₁', '۱'],
            '2' => ['²', '₂', '۲'],
            '3' => ['³', '₃', '۳'],
            '4' => ['⁴', '₄', '۴', '٤'],
            '5' => ['⁵', '₅', '۵', '٥'],
            '6' => ['⁶', '₆', '۶', '٦'],
            '7' => ['⁷', '₇', '۷'],
            '8' => ['⁸', '₈', '۸'],
            '9' => ['⁹', '₉', '۹'],
            'a' => ['à', 'á', 'ả', 'ã', 'ạ', 'ă', 'ắ', 'ằ', 'ẳ', 'ẵ', 'ặ', 'â', 'ấ', 'ầ', 'ẩ', 'ẫ', 'ậ', 'ā', 'ą', 'å', 'α', 'ά', 'ἀ', 'ἁ', 'ἂ', 'ἃ', 'ἄ', 'ἅ', 'ἆ', 'ἇ', 'ᾀ', 'ᾁ', 'ᾂ', 'ᾃ', 'ᾄ', 'ᾅ', 'ᾆ', 'ᾇ', 'ὰ', 'ά', 'ᾰ', 'ᾱ', 'ᾲ', 'ᾳ', 'ᾴ', 'ᾶ', 'ᾷ', 'а', 'أ', 'အ', 'ာ', 'ါ', 'ǻ', 'ǎ', 'ª', 'ა', 'अ', 'ا'],
            'b' => ['б', 'β', 'Ъ', 'Ь', 'ب', 'ဗ', 'ბ'],
            'c' => ['ç', 'ć', 'č', 'ĉ', 'ċ'],
            'd' => ['ď', 'ð', 'đ', 'ƌ', 'ȡ', 'ɖ', 'ɗ', 'ᵭ', 'ᶁ', 'ᶑ', 'д', 'δ', 'د', 'ض', 'ဍ', 'ဒ', 'დ'],
            'e' => ['é', 'è', 'ẻ', 'ẽ', 'ẹ', 'ê', 'ế', 'ề', 'ể', 'ễ', 'ệ', 'ë', 'ē', 'ę', 'ě', 'ĕ', 'ė', 'ε', 'έ', 'ἐ', 'ἑ', 'ἒ', 'ἓ', 'ἔ', 'ἕ', 'ὲ', 'έ', 'е', 'ё', 'э', 'є', 'ə', 'ဧ', 'ေ', 'ဲ', 'ე', 'ए', 'إ', 'ئ'],
            'f' => ['ф', 'φ', 'ف', 'ƒ', 'ფ'],
            'g' => ['ĝ', 'ğ', 'ġ', 'ģ', 'г', 'ґ', 'γ', 'ဂ', 'გ', 'گ'],
            'h' => ['ĥ', 'ħ', 'η', 'ή', 'ح', 'ه', 'ဟ', 'ှ', 'ჰ'],
            'i' => ['í', 'ì', 'ỉ', 'ĩ', 'ị', 'î', 'ï', 'ī', 'ĭ', 'į', 'ı', 'ι', 'ί', 'ϊ', 'ΐ', 'ἰ', 'ἱ', 'ἲ', 'ἳ', 'ἴ', 'ἵ', 'ἶ', 'ἷ', 'ὶ', 'ί', 'ῐ', 'ῑ', 'ῒ', 'ΐ', 'ῖ', 'ῗ', 'і', 'ї', 'и', 'ဣ', 'ိ', 'ီ', 'ည်', 'ǐ', 'ი', 'इ'],
            'j' => ['ĵ', 'ј', 'Ј', 'ჯ', 'ج'],
            'k' => ['ķ', 'ĸ', 'к', 'κ', 'Ķ', 'ق', 'ك', 'က', 'კ', 'ქ', 'ک'],
            'l' => ['ł', 'ľ', 'ĺ', 'ļ', 'ŀ', 'л', 'λ', 'ل', 'လ', 'ლ'],
            'm' => ['м', 'μ', 'م', 'မ', 'მ'],
            'n' => ['ñ', 'ń', 'ň', 'ņ', 'ŉ', 'ŋ', 'ν', 'н', 'ن', 'န', 'ნ'],
            'o' => ['ó', 'ò', 'ỏ', 'õ', 'ọ', 'ô', 'ố', 'ồ', 'ổ', 'ỗ', 'ộ', 'ơ', 'ớ', 'ờ', 'ở', 'ỡ', 'ợ', 'ø', 'ō', 'ő', 'ŏ', 'ο', 'ὀ', 'ὁ', 'ὂ', 'ὃ', 'ὄ', 'ὅ', 'ὸ', 'ό', 'о', 'و', 'θ', 'ို', 'ǒ', 'ǿ', 'º', 'ო', 'ओ'],
            'p' => ['п', 'π', 'ပ', 'პ', 'پ'],
            'q' => ['ყ'],
            'r' => ['ŕ', 'ř', 'ŗ', 'р', 'ρ', 'ر', 'რ'],
            's' => ['ś', 'š', 'ş', 'с', 'σ', 'ș', 'ς', 'س', 'ص', 'စ', 'ſ', 'ს'],
            't' => ['ť', 'ţ', 'т', 'τ', 'ț', 'ت', 'ط', 'ဋ', 'တ', 'ŧ', 'თ', 'ტ'],
            'u' => ['ú', 'ù', 'ủ', 'ũ', 'ụ', 'ư', 'ứ', 'ừ', 'ử', 'ữ', 'ự', 'û', 'ū', 'ů', 'ű', 'ŭ', 'ų', 'µ', 'у', 'ဉ', 'ု', 'ူ', 'ǔ', 'ǖ', 'ǘ', 'ǚ', 'ǜ', 'უ', 'उ'],
            'v' => ['в', 'ვ', 'ϐ'],
            'w' => ['ŵ', 'ω', 'ώ', 'ဝ', 'ွ'],
            'x' => ['χ', 'ξ'],
            'y' => ['ý', 'ỳ', 'ỷ', 'ỹ', 'ỵ', 'ÿ', 'ŷ', 'й', 'ы', 'υ', 'ϋ', 'ύ', 'ΰ', 'ي', 'ယ'],
            'z' => ['ź', 'ž', 'ż', 'з', 'ζ', 'ز', 'ဇ', 'ზ'],
            'aa' => ['ع', 'आ', 'آ'],
            'ae' => ['ä', 'æ', 'ǽ'],
            'ai' => ['ऐ'],
            'at' => ['@'],
            'ch' => ['ч', 'ჩ', 'ჭ', 'چ'],
            'dj' => ['ђ', 'đ'],
            'dz' => ['џ', 'ძ'],
            'ei' => ['ऍ'],
            'gh' => ['غ', 'ღ'],
            'ii' => ['ई'],
            'ij' => ['ĳ'],
            'kh' => ['х', 'خ', 'ხ'],
            'lj' => ['љ'],
            'nj' => ['њ'],
            'oe' => ['ö', 'œ', 'ؤ'],
            'oi' => ['ऑ'],
            'oii' => ['ऒ'],
            'ps' => ['ψ'],
            'sh' => ['ш', 'შ', 'ش'],
            'shch' => ['щ'],
            'ss' => ['ß'],
            'sx' => ['ŝ'],
            'th' => ['þ', 'ϑ', 'ث', 'ذ', 'ظ'],
            'ts' => ['ц', 'ც', 'წ'],
            'ue' => ['ü'],
            'uu' => ['ऊ'],
            'ya' => ['я'],
            'yu' => ['ю'],
            'zh' => ['ж', 'ჟ', 'ژ'],
            '(c)' => ['©'],
            'A' => ['Á', 'À', 'Ả', 'Ã', 'Ạ', 'Ă', 'Ắ', 'Ằ', 'Ẳ', 'Ẵ', 'Ặ', 'Â', 'Ấ', 'Ầ', 'Ẩ', 'Ẫ', 'Ậ', 'Å', 'Ā', 'Ą', 'Α', 'Ά', 'Ἀ', 'Ἁ', 'Ἂ', 'Ἃ', 'Ἄ', 'Ἅ', 'Ἆ', 'Ἇ', 'ᾈ', 'ᾉ', 'ᾊ', 'ᾋ', 'ᾌ', 'ᾍ', 'ᾎ', 'ᾏ', 'Ᾰ', 'Ᾱ', 'Ὰ', 'Ά', 'ᾼ', 'А', 'Ǻ', 'Ǎ'],
            'B' => ['Б', 'Β', 'ब'],
            'C' => ['Ç', 'Ć', 'Č', 'Ĉ', 'Ċ'],
            'D' => ['Ď', 'Ð', 'Đ', 'Ɖ', 'Ɗ', 'Ƌ', 'ᴅ', 'ᴆ', 'Д', 'Δ'],
            'E' => ['É', 'È', 'Ẻ', 'Ẽ', 'Ẹ', 'Ê', 'Ế', 'Ề', 'Ể', 'Ễ', 'Ệ', 'Ë', 'Ē', 'Ę', 'Ě', 'Ĕ', 'Ė', 'Ε', 'Έ', 'Ἐ', 'Ἑ', 'Ἒ', 'Ἓ', 'Ἔ', 'Ἕ', 'Έ', 'Ὲ', 'Е', 'Ё', 'Э', 'Є', 'Ə'],
            'F' => ['Ф', 'Φ'],
            'G' => ['Ğ', 'Ġ', 'Ģ', 'Г', 'Ґ', 'Γ'],
            'H' => ['Η', 'Ή', 'Ħ'],
            'I' => ['Í', 'Ì', 'Ỉ', 'Ĩ', 'Ị', 'Î', 'Ï', 'Ī', 'Ĭ', 'Į', 'İ', 'Ι', 'Ί', 'Ϊ', 'Ἰ', 'Ἱ', 'Ἳ', 'Ἴ', 'Ἵ', 'Ἶ', 'Ἷ', 'Ῐ', 'Ῑ', 'Ὶ', 'Ί', 'И', 'І', 'Ї', 'Ǐ', 'ϒ'],
            'K' => ['К', 'Κ'],
            'L' => ['Ĺ', 'Ł', 'Л', 'Λ', 'Ļ', 'Ľ', 'Ŀ', 'ल'],
            'M' => ['М', 'Μ'],
            'N' => ['Ń', 'Ñ', 'Ň', 'Ņ', 'Ŋ', 'Н', 'Ν'],
            'O' => ['Ó', 'Ò', 'Ỏ', 'Õ', 'Ọ', 'Ô', 'Ố', 'Ồ', 'Ổ', 'Ỗ', 'Ộ', 'Ơ', 'Ớ', 'Ờ', 'Ở', 'Ỡ', 'Ợ', 'Ø', 'Ō', 'Ő', 'Ŏ', 'Ο', 'Ό', 'Ὀ', 'Ὁ', 'Ὂ', 'Ὃ', 'Ὄ', 'Ὅ', 'Ὸ', 'Ό', 'О', 'Θ', 'Ө', 'Ǒ', 'Ǿ'],
            'P' => ['П', 'Π'],
            'R' => ['Ř', 'Ŕ', 'Р', 'Ρ', 'Ŗ'],
            'S' => ['Ş', 'Ŝ', 'Ș', 'Š', 'Ś', 'С', 'Σ'],
            'T' => ['Ť', 'Ţ', 'Ŧ', 'Ț', 'Т', 'Τ'],
            'U' => ['Ú', 'Ù', 'Ủ', 'Ũ', 'Ụ', 'Ư', 'Ứ', 'Ừ', 'Ử', 'Ữ', 'Ự', 'Û', 'Ū', 'Ů', 'Ű', 'Ŭ', 'Ų', 'У', 'Ǔ', 'Ǖ', 'Ǘ', 'Ǚ', 'Ǜ'],
            'V' => ['В'],
            'W' => ['Ω', 'Ώ', 'Ŵ'],
            'X' => ['Χ', 'Ξ'],
            'Y' => ['Ý', 'Ỳ', 'Ỷ', 'Ỹ', 'Ỵ', 'Ÿ', 'Ῠ', 'Ῡ', 'Ὺ', 'Ύ', 'Ы', 'Й', 'Υ', 'Ϋ', 'Ŷ'],
            'Z' => ['Ź', 'Ž', 'Ż', 'З', 'Ζ'],
            'AE' => ['Ä', 'Æ', 'Ǽ'],
            'CH' => ['Ч'],
            'DJ' => ['Ђ'],
            'DZ' => ['Џ'],
            'GX' => ['Ĝ'],
            'HX' => ['Ĥ'],
            'IJ' => ['Ĳ'],
            'JX' => ['Ĵ'],
            'KH' => ['Х'],
            'LJ' => ['Љ'],
            'NJ' => ['Њ'],
            'OE' => ['Ö', 'Œ'],
            'PS' => ['Ψ'],
            'SH' => ['Ш'],
            'SHCH' => ['Щ'],
            'SS' => ['ẞ'],
            'TH' => ['Þ'],
            'TS' => ['Ц'],
            'UE' => ['Ü'],
            'YA' => ['Я'],
            'YU' => ['Ю'],
            'ZH' => ['Ж'],
            ' ' => ["\xC2\xA0", "\xE2\x80\x80", "\xE2\x80\x81", "\xE2\x80\x82", "\xE2\x80\x83", "\xE2\x80\x84", "\xE2\x80\x85", "\xE2\x80\x86", "\xE2\x80\x87", "\xE2\x80\x88", "\xE2\x80\x89", "\xE2\x80\x8A", "\xE2\x80\xAF", "\xE2\x81\x9F", "\xE3\x80\x80"],
        ];
    }
}
