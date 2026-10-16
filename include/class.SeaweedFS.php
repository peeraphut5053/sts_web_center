<?php
/**
 * SeaweedFS Client for STS Web Center
 * Direct HTTP REST API via SeaweedFS Filer (port 8888)
 */
class SeaweedFS {
    private static $filerUrl = "http://172.18.1.245:8888";

    public static function getFilerUrl() {
        return self::$filerUrl;
    }

    /**
     * Get candidate URLs for a file (both with and without 'buckets/' prefix)
     */
    public static function getCandidateUrls($path, $filename) {
        $cleanPath = trim(str_replace('\\', '/', $path), '/');
        $cleanFile = rawurlencode(basename($filename));
        $urls = [];
        if (!preg_match('/^buckets(\/|$)/i', $cleanPath)) {
            $urls[] = rtrim(self::$filerUrl, '/') . '/buckets/' . $cleanPath . '/' . $cleanFile;
        }
        $urls[] = rtrim(self::$filerUrl, '/') . '/' . $cleanPath . '/' . $cleanFile;
        return $urls;
    }

    /**
     * Build standard URL for a file in SeaweedFS (defaults to /buckets/<path>/<file>)
     */
    public static function getUrl($path, $filename) {
        $cleanPath = trim(str_replace('\\', '/', $path), '/');
        if (!preg_match('/^buckets(\/|$)/i', $cleanPath)) {
            $cleanPath = 'buckets/' . $cleanPath;
        }
        $cleanFile = rawurlencode(basename($filename));
        return rtrim(self::$filerUrl, '/') . '/' . $cleanPath . '/' . $cleanFile;
    }

    /**
     * Upload local file or uploaded temp file to SeaweedFS bucket
     * @param string $path e.g. 'po-pic' or 'buckets/po-pic'
     * @param string $filename target filename e.g. 'PO26003576_1_20261016_120000.pdf'
     * @param string $localFilePath path to local/temp file
     * @return bool
     */
    public static function upload($path, $filename, $localFilePath) {
        if (!file_exists($localFilePath)) {
            return false;
        }

        $url = self::getUrl($path, $filename);
        $fileSize = filesize($localFilePath);
        $fp = @fopen($localFilePath, 'rb');
        if (!$fp) {
            return false;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_PUT, true);
        curl_setopt($ch, CURLOPT_INFILE, $fp);
        curl_setopt($ch, CURLOPT_INFILESIZE, $fileSize);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        $res = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        fclose($fp);

        return ($httpCode >= 200 && $httpCode < 300);
    }

    /**
     * Check if file exists in SeaweedFS (checks both /buckets/<path> and /<path>)
     * @param string $path
     * @param string $filename
     * @return bool
     */
    public static function exists($path, $filename) {
        $urls = self::getCandidateUrls($path, $filename);
        foreach ($urls as $url) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

            curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get binary content of file
     * @param string $path
     * @param string $filename
     * @return string|false
     */
    public static function getContents($path, $filename) {
        $urls = self::getCandidateUrls($path, $filename);
        foreach ($urls as $url) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

            $content = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                return $content;
            }
        }

        return false;
    }

    /**
     * Stream file directly to output buffer (e.g. for PDF inline preview or image)
     * @param string $path
     * @param string $filename
     * @param string $contentType
     * @return bool
     */
    public static function stream($path, $filename, $contentType = 'application/pdf') {
        $urls = self::getCandidateUrls($path, $filename);

        foreach ($urls as $url) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $contentLength = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
            curl_close($ch);

            if ($httpCode === 200) {
                header("Content-Type: " . $contentType);
                header('Content-Disposition: inline; filename="' . basename($filename) . '"');
                if ($contentLength > 0) {
                    header('Content-Length: ' . $contentLength);
                }
                header('Cache-Control: private, no-store');
                header('X-Content-Type-Options: nosniff');

                $chStream = curl_init();
                curl_setopt($chStream, CURLOPT_URL, $url);
                curl_setopt($chStream, CURLOPT_RETURNTRANSFER, false);
                curl_setopt($chStream, CURLOPT_TIMEOUT, 60);
                curl_exec($chStream);
                curl_close($chStream);

                return true;
            }
        }

        return false;
    }

    /**
     * Delete file from SeaweedFS
     * @param string $path
     * @param string $filename
     * @return bool
     */
    public static function delete($path, $filename) {
        $urls = self::getCandidateUrls($path, $filename);
        $deleted = false;
        foreach ($urls as $url) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);

            curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if (($httpCode >= 200 && $httpCode < 300) || $httpCode === 404) {
                $deleted = true;
            }
        }

        return $deleted;
    }
}
