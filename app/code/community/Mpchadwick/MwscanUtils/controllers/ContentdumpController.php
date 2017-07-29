<?php

class Mpchadwick_MwscanUtils_ContentdumpController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $data = array_merge(
            Mage::getModel('cms/page')
                ->getCollection()
                ->getData(),
            Mage::getModel('cms/block')
                ->getCollection()
                ->getData()
        );

        $content = array();
        foreach ($data as $datum) {
            $content[] = $datum['content'];
        }

        // @codingStandardsIgnoreStart
        // TODO - Fetch for ALL store view...
        // @codingStandardsIgnoreEnd
        $content[] = Mage::getStoreConfig('design/head/includes');
        $content[] = Mage::getStoreConfig('design/footer/absolute_footer');

        $container = new Varien_Object;
        $container->setContent($content);
        Mage::dispatchEvent(
            'mpchadwick_mwscanutils_dump_content_before',
            array('container' => $container)
        );

        $response = $this->getResponse();
        $response->setHeader('Content-Type', 'text/plain', true);
        $response->appendBody(implode(PHP_EOL . PHP_EOL . '----' .  PHP_EOL . PHP_EOL, $container->getContent()));
    }
}
