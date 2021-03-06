<?php

/**
 * This file is part of the Xi FilelibBundle package.
 *
 * For copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xi\Bundle\FilelibBundle\Twig\Extension;

use Xi\Filelib\File\FileRepository;
use Xi\Filelib\FileLibrary;
use Xi\Filelib\Renderer\Renderer;
use Xi\Filelib\Publisher\Publisher;
use Xi\Filelib\File\File;
use Symfony\Component\Routing\RouterInterface;
use InvalidArgumentException;
use Xi\Filelib\Storage\FileIOException;
use Xi\Filelib\Attacher;
use Twig_SimpleFunction;

class FilelibExtension extends \Twig_Extension implements Attacher
{
    /**
     * @var FileRepository;
     */
    protected $fileOperator;

    /**
     * @var Renderer
     */
    protected $renderer;

    /**
     * @var Publisher
     */
    protected $publisher;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var string
     */
    protected $notFoundUrl;

    protected $defaultOptions = array(
        'version' => 'original',
        'download' => false,
        'track' => false
    );

    public function __construct(
        Publisher $publisher,
        Renderer $renderer,
        RouterInterface $router,
        $notFoundUrl
    ) {
        $this->publisher = $publisher;
        $this->renderer = $renderer;
        $this->router = $router;
        $this->notFoundUrl = $notFoundUrl;
    }

    public function attachTo(FileLibrary $filelib)
    {
        $this->fileOperator = $filelib->getFileRepository();
    }

    private function mergeOptionsWithDefaultOptions($options)
    {
        return array_merge($this->defaultOptions, $options);
    }

    public function getFunctions()
    {
        return array(
            'filelib_file' => new Twig_SimpleFunction('filelib_file', array($this, 'getFile'), array('is_safe' => array('html'))),
            'filelib_url' => new Twig_SimpleFunction('filelib_url', array($this, 'getFileUrl'), array('is_safe' => array('html'))),
            'filelib_render' => new Twig_SimpleFunction('filelib_render', array($this, 'getRenderUrl'), array('is_safe' => array('html'))),
            'filelib_is_file_completed' => new Twig_SimpleFunction('filelib_is_file_completed', array($this, 'isFileCompleted')),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'xi_filelib';
    }

    public function getFile($file, $version = 'original', $options = array())
    {
        $file = $this->assertFileIsValid($file);
        if ($this->publisher->isPublished($file)) {
            return $this->getFileUrl($file, $version, $options);
        }
        return $this->getRenderUrl($file, $version, $options);
    }

    public function getFileUrl($file, $version = 'original', $options = array())
    {
        $file = $this->assertFileIsValid($file);

        $options = $this->mergeOptionsWithDefaultOptions($options);

        if ($file->hasVersion($version) || $file->getResource()->hasVersion($version)) {
            try {
                return $this->publisher->getUrlVersion($file, $version, $options);
            } catch (FileIOException $e) {
                return $this->notFoundUrl;
            }
        } else {
            return $this->notFoundUrl;
        }


    }

    public function getRenderUrl($file, $version = 'original', $options = array())
    {
        $file = $this->assertFileIsValid($file);
        $options['version'] = $version;
        $options['id'] = $file->getId();
        $options = $this->mergeOptionsWithDefaultOptions($options);
        $url = $this->router->generate('xi_filelib_render', $options);

        return $url;
    }

    /**
     * @param  integer|string|File $file
     * @return boolean
     */
    public function isFileCompleted($file)
    {
        $file = $this->assertFileIsValid($file);

        return $file->getStatus() === File::STATUS_COMPLETED;
    }

    /**
     * Asserts that file is valid
     *
     * @param  integer|string|File      $file
     * @return File
     * @throws InvalidArgumentException
     */
    private function assertFileIsValid($file)
    {
        if (is_numeric($file)) {
            $file = $this->fileOperator->find($file);
        }

        if (!$file instanceof File) {
            throw new InvalidArgumentException('Invalid file');
        }

        return $file;
    }
}
