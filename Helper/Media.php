<?php

namespace Bluebadger\JasperPim\Helper;

use Magento\Framework\File\Uploader;

class Media
{

    /**
     * @var \Magento\Catalog\Model\Product\Media\Config
     */
    private $config;
    /**
     * @var \Magento\Framework\Filesystem\DirectoryList
     */
    private $directoryList;
    /**
     * @var \Magento\Framework\Filesystem\Io\File
     */
    private $filesystemIo;
    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    private $directory;
    /**
     * @var \Bluebadger\JasperPim\Model\Logger
     */
    private $logger;

    public function __construct(
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Filesystem\Io\File $filesystemIo,
        \Magento\Catalog\Model\Product\Media\Config $config,
        \Bluebadger\JasperPim\Model\Logger $logger,
        \Magento\Framework\Filesystem\DirectoryList $directoryList
    ) {
        $this->config = $config;
        $this->filesystemIo = $filesystemIo;
        $this->logger = $logger;
        $this->directoryList = $directoryList;
        $this->directory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
    }

    public function saveProductImage($url)
    {
        $filename = $this->filesystemIo->getPathInfo($url)['basename'];
        $filename = Uploader::getDispersionPath($filename) .
            DIRECTORY_SEPARATOR .
            Uploader::getCorrectFileName($filename);

        $path = $this->config->getMediaPath($filename);
        if (!$this->directory->isFile($filename)) {
            $client = new \GuzzleHttp\Client();
            $this->directory->writeFile($path, '');
            $client->request('GET', $url, ['sink' => $this->directory->getAbsolutePath() . $path]);
        }

        return $filename;
    }

    public function saveCategoryImage($url)
    {
        $this->logger->debug('will try to download image: ' . $url);
        $toFolder = 'pub/media/catalog/category';
        $filename = $this->filesystemIo->getPathInfo($url)['basename'];

        $client = new \GuzzleHttp\Client();
        $filename = \Magento\Framework\File\Uploader::getCorrectFileName($filename);
        $folderPath = $this->directoryList->getRoot() . DIRECTORY_SEPARATOR . $toFolder;

        if (!$this->filesystemIo->fileExists($folderPath, false)) {
            $this->filesystemIo->mkdir($folderPath);
        }
        $filePath = $folderPath . DIRECTORY_SEPARATOR . $filename;
        if (!$this->filesystemIo->fileExists($filePath)) {
            $this->logger->debug('Will download image ...');
            $client->request('GET', $url, ['sink' => $filePath]);
            $this->logger->debug('Image downloaded.');
        }
        return $this->filesystemIo->getPathInfo($filePath)['basename'];
    }
}
