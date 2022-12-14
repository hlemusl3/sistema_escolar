
<?php
// +------------------------------------------------------------------------+
// | class.upload.tr_TR.php                                                 |
// +------------------------------------------------------------------------+
// | Copyright (c) Volkan Metin 2008. All rights reserved.                  |
// | Version       0.32                                                     |
// | Last modified 30/08/2013                                               |
// | Email         metinsoft@gmail.com                                      |
// | Web           http://www.metinsoft.com                                 |
// +------------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or modify   |
// | it under the terms of the GNU General Public License version 2 as      |
// | published by the Free Software Foundation.                             |
// |                                                                        |
// | This program is distributed in the hope that it will be useful,        |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of         |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the          |
// | GNU General Public License for more details.                           |
// |                                                                        |
// | You should have received a copy of the GNU General Public License      |
// | along with this program; if not, write to the                          |
// |   Free Software Foundation, Inc., 59 Temple Place, Suite 330,          |
// |   Boston, MA 02111-1307 USA                                            |
// |                                                                        |
// | Please give credit on sites that use class.upload and submit changes   |
// | of the script so other people can use them as well.                    |
// | This script is free to use, don't abuse.                               |
// +------------------------------------------------------------------------+

/**
 * Class upload Turkish translation
 *
 * @version   0.25
 * @author    Volkan Metin (metinsoft@gmail.com)
 * @license   http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright Volkan Metin
 * @edit Taner ??nan??r
 * @edit Halil ??afak KILI??
 * @package   cmf
 * @subpackage external
 */

    $translation = array();
    $translation['file_error']                  = 'Hata olu??tu. L??tfen tekrar deneyiniz.';
    $translation['local_file_missing']          = 'Dosya bulunamad??.';
    $translation['local_file_not_readable']     = 'Dosya okunamad??.';
    $translation['uploaded_too_big_ini']        = 'Hata olu??tu (izin verilen boyuttan b??y??k dosya y??kleyemezsiniz. Ancak php.ini dosyas??ndan upload_max_filesize de??erini y??kselterek tekrar deneyebilirsiniz.).';
    $translation['uploaded_too_big_html']       = 'Hata olu??tu (sayfan??zda belirtti??iniz MAX_FILE_SIZE boyutundan b??y??k bir dosya y??kleyemezsiniz.).';
    $translation['uploaded_partial']            = 'Hata olu??tu (dosyan??n sadece bir k??sm?? y??klenebildi).';
    $translation['uploaded_missing']            = 'Hata olu??tu (dosya se??ilmemi??).';
    $translation['uploaded_unknown']            = 'Hata olu??tu (hata tespit edilemedi).';
    $translation['try_again']                   = 'Hata olu??tu. L??tfen tekrar deneyiniz.';
    $translation['file_too_big']                = 'Dosya izin verilenden daha b??y??k.';
    $translation['no_mime']                     = 'Dosya t??r?? bulunamad??.';
    $translation['incorrect_file']              = 'Bu dosyan??n uzant??s?? ge??ersiz.';
    $translation['image_too_wide']              = 'G??rsel izin verilenden daha geni??.';
    $translation['image_too_narrow']            = 'G??rsel izin verilenden daha dar.';
    $translation['image_too_high']              = 'G??rsel izin verilenden daha uzun.';
    $translation['image_too_short']             = 'G??rsel izin verilenden daha k??sa.';
    $translation['ratio_too_high']              = 'G??rsel oran?? ??ok y??ksek (g??rsel ??ok geni??).';
    $translation['ratio_too_low']               = 'G??rsel oran?? ??ok d??????k (g??rsel ??ok uzun).';
    $translation['too_many_pixels']             = 'G??rsel izin verilenden b??y??k.';
    $translation['not_enough_pixels']           = 'G??rsel izin verilenden k??????k.';
    $translation['file_not_uploaded']           = 'Dosya y??klenemedi. ????lem sonland??r??ld??.';
    $translation['already_exists']              = '%s dosyas?? zaten var. L??tfen dosyan??z??n ismini de??i??tirerek tekrar deneyiniz.';
    $translation['temp_file_missing']           = 'Temp dizini do??ru belirtilmemi??. ????lem sonland??r??ld??.';
    $translation['source_missing']              = 'Dosyan??z??n i??eri??inde izin verilmeyen unsurlar var. ????lem sonland??r??ld??.';
    $translation['destination_dir']             = 'Dosyalar??n y??klenece??i dizin olu??turulamad??. ????lem sonland??r??ld??.';
    $translation['destination_dir_missing']     = 'Dosyalar??n y??klenece??i dizin olu??turulmam????. ????lem sonland??r??ld??.';
    $translation['destination_path_not_dir']    = 'Dosyalar??n y??klenece??i adres bir dizin de??il. ????lem sonland??r??ld??.';
    $translation['destination_dir_write']       = 'Dosyalar??n y??klenece??i dizinin yazma izinlerinde(CHMOD) problem var. ????lem sonland??r??ld??.';
    $translation['destination_path_write']      = 'Dosyalar??n y??klenece??i adresin yazma izinlerinde(CHMOD) problem var. ????lem sonland??r??ld??.';
    $translation['temp_file']                   = 'Ge??ici dizine (temp) yaz??lam??yor. ??zinleri kontrol etmelisiniz. ????lem sonland??r??ld??.';
    $translation['source_not_readable']         = 'Dosyan??n i??eri??i okunamad??. ????lem sonland??r??ld??.';
    $translation['no_create_support']           = '%s dosyas?? olu??turulamad??.';
    $translation['create_error']                = 'Kaynaktan %s resmi olu??turulurken hata olu??tu.';
    $translation['source_invalid']              = 'G??rsel dosyas?? okunamad??. Dosyan??n bir g??rsel oldu??undan emin misiniz?';
    $translation['gd_missing']                  = 'Sunucuda GD k??t??phanesi olmad?????? i??in i??leme devam edemiyorsunuz.';
    $translation['watermark_no_create_support'] = '%s resmi olu??turulamad?????? i??in filigran olu??turulamad??.';
    $translation['watermark_create_error']      = '%s resmi okunamad?????? i??in filigran olu??turulamad??.';
    $translation['watermark_invalid']           = 'Bilinmeyen dosya t??r??. Filigran olu??turulamad??.';
    $translation['file_create']                 = '%s dosyas?? olu??turulamad??.';
    $translation['no_conversion_type']          = 'Belirtilen dosya t??r?? d??n????t??r??lemedi.';
    $translation['copy_failed']                 = 'Dosya kopyalan??rken hata olu??tu. copy() i??lemi ba??ar??s??z.';
    $translation['reading_failed']              = 'Dosya okunurken hata olu??tu.';   
