<?php

namespace CRON\Behat;

use Behat\Behat\Event\StepEvent;
use Behat\Mink\Driver\Selenium2Driver;

/**
 * Include this trait in your FeatureContext in order to dump the HTML content after each step.
 * Will also make a screenshot after each step in case of SeleniumDriver.
 *
 * Artefacts are stored in the path specified by the CIRCLE_ARTIFACTS environment variable.
 *
 * Trait DumpContentTrait
 * @package CRON\Behat
 */
trait DumpContentTrait
{

    /**
     * Used to compare the last content with the new one, to decide if we want to dump content
     *
     * @var string
     */
    protected $lastContent = null;

    /**
     * Take a Screenshot and/or save the HTML in the "CircleCI Artifacts" directory
     *
     * Notes:
     * - only relevant in CircleCI environments
     * - Screenshots (PNG) only with Selenium2Driver
     *
     * @param StepEvent $event
     *
     * @AfterStep
     */
    public function dumpContentAfterStep(StepEvent $event)
    {
        switch ($event->getResult()) {
            case StepEvent::SKIPPED:
            case StepEvent::PENDING:
            case StepEvent::UNDEFINED:
                return;
        }

        if ($artifactsPath = getenv('CIRCLE_ARTIFACTS')) {
            $driver = $this->getSession()->getDriver();

            $content = null;

            if (($driver instanceof Selenium2Driver)) {
                // the screenshot feature is only available in the Selenium Driver
                $screenshot = $driver->getScreenshot();
                $content = $driver->getContent();
                file_put_contents($artifactsPath . '/' . $this->getArtifactFilename($event, 'png'), $screenshot);

            } elseif ($driver instanceof \Behat\Mink\Driver\BrowserKitDriver) {
                // get only the content if there is a Response object
                $response = $driver->getClient()->getResponse();
                if ($response) {
                    $content = $driver->getContent();
                }
            }

            if (!empty($content) && $content !== $this->lastContent) {
                // Only dump html if it changed since last step
                file_put_contents($artifactsPath . '/' . $this->getArtifactFilename($event, 'html'), $content);
                $this->lastContent = $content;
            }

        }
    }

    /**
     * Get a filename to store an artifact specific for the currently being executed event / step
     *
     * @param StepEvent $event
     * @param string $extension File extension to use
     *
     * @return string
     */
    protected function getArtifactFilename(StepEvent $event, $extension)
    {
        $fileNameCurated = str_replace('/', '_',
            preg_replace('/^.+\/Features\//', '', $event->getStep()->getFile()
            ));

        return sprintf('%s_line_%d.%s', $fileNameCurated, $event->getStep()->getLine(), $extension);
    }

}
