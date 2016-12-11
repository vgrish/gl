<?php

/** @var $modx modX */
if (!$modx = $object->xpdo AND !$object->xpdo instanceof modX) {
    return true;
}

/** @var $options */
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:

        if (!class_exists('PclZip')) {
            require MODX_CORE_PATH . 'xpdo/compression/pclzip.lib.php';
        }

        $cacheManager = $modx->getCacheManager();

        $vendors = array(
            array(
                'colorbox',
                'colorbox',
                'https://github.com/jackmoore/colorbox/archive/master.zip',
                MODX_ASSETS_PATH . 'components/gl/vendor/'
            ),
            array(
                'select2',
                'select2',
                'https://github.com/select2/select2/archive/master.zip',
                MODX_ASSETS_PATH . 'components/gl/vendor/'
            ),
            array(
                'uikit',
                'uikit',
                'https://github.com/uikit/uikit/archive/develop.zip',
                MODX_ASSETS_PATH . 'components/gl/vendor/'
            ),
            array(
                'sxgeocity',
                '',
                'http://sypexgeo.net/files/SxGeoCity_utf8.zip',
                MODX_ASSETS_PATH . 'components/gl/vendor/sypexgeo/data/'
            ),
            array(
                'sxgeoinfo',
                '',
                'http://sypexgeo.net/files/SxGeo_Info.zip',
                MODX_ASSETS_PATH . 'components/gl/vendor/sypexgeo/info/'
            ),
        );


        foreach ($vendors as $vendor) {
            list($name, $rename, $url, $path) = $vendor;
            $tmp = $name . '.zip';

            /* does not exist */
            if (!file_exists($path) OR !is_dir($path)) {
                if (!$cacheManager->writeTree($path)) {
                    $modx->log(xPDO::LOG_LEVEL_INFO, "Could not create directory: " . $path);
                }
            }

            if (file_exists($path . '.' . $name)) {
                $modx->log(modX::LOG_LEVEL_INFO, "Trying to delete old <b>{$name}</b> files. Please wait...");
                $cacheManager->deleteTree($path,
                    array_merge(array('deleteTop' => false, 'skipDirs' => false, 'extensions' => array())));
            }

            $modx->log(modX::LOG_LEVEL_INFO, "Trying to download <b>{$name}</b>. Please wait...");
            glDownload($url, $path . $tmp);

            $file = new PclZip($path . $tmp);
            if ($files = $file->extract(PCLZIP_OPT_PATH, $path)) {
                unlink($path . $tmp);
                file_put_contents($path . '.' . $name, date('Y-m-d H:i:s'));

                if (!empty($rename)) {
                    $dirname = rtrim($files[0]['filename'], '/');
                    /* rename dir */
                    $ddir = explode('/', $dirname);
                    $rdir = array_pop($ddir);
                    $separated = implode('/', $ddir);
                    $ndir = $separated . '/' . $rename;
                    if ($dirname != $ndir) {
                        if (!@rename($dirname, $ndir)) {
                            $modx->log(xPDO::LOG_LEVEL_INFO, "Could not rename <b>{$ndir}</b>");
                        }
                    }
                }

                $modx->log(modX::LOG_LEVEL_INFO, "<b>{$name}</b> was successfully installed");
            } else {
                $modx->log(xPDO::LOG_LEVEL_INFO,
                    "Could not extract <b>{$name}</b> from <b>{$tmp}</b> to <b>{$path}</b>. Error: " . $file->errorInfo());
            }

        }

        break;

    case xPDOTransport::ACTION_UNINSTALL:
        break;
}

return true;

/**
 * Download file
 *
 * @param $src
 * @param $dst
 *
 * @return bool
 */
function glDownload($src, $dst)
{
    if (ini_get('allow_url_fopen')) {
        $file = @file_get_contents($src);
    } else {
        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $src);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 180);
            $safeMode = @ini_get('safe_mode');
            $openBasedir = @ini_get('open_basedir');
            if (empty($safeMode) && empty($openBasedir)) {
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            }
            $file = curl_exec($ch);
            curl_close($ch);
        } else {
            return false;
        }
    }
    file_put_contents($dst, $file);

    return file_exists($dst);
}
