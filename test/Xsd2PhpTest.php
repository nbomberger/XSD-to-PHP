<?php

use com\mikebevz\xsd2php;
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__)."/../src/Xsd2Php.php";

class Xsd2PhpTest extends PHPUnit_Framework_TestCase
{
    /**
     * XSD to PHP convertor class
     * @var Xsd2Php
     */
    private $tclass; 
    
    private $xsd;
    
    protected function setUp ()
    {
        $this->xsd = dirname(__FILE__)."/../resources/ubl2.0/maindoc/UBL-Order-2.0.xsd";
        $this->tclass = new xsd2php\Xsd2Php($this->xsd);
    }
    protected function tearDown ()
    {
        $this->tclass = null;        
    }
    
    private function rmdir_recursive($dir) {
        if (is_dir($dir)) { 
         $objects = scandir($dir); 
         foreach ($objects as $object) { 
           if ($object != "." && $object != "..") { 
             if (filetype($dir."/".$object) == "dir") $this->rmdir_recursive($dir."/".$object); else unlink($dir."/".$object); 
           } 
         } 
         reset($objects); 
         rmdir($dir); 
       } 
    }
    
    public function testXSDMustBeConvertedToXML() {
        $xml = $this->tclass->getXML();
        $actual = $xml->saveXml();
        //file_put_contents(dirname(__FILE__).'/data/expected/ubl2.0/XSDConvertertoXML.xml', $xml->saveXml());
        $expected = file_get_contents(dirname(__FILE__).'/data/expected/ubl2.0/XSDConvertertoXML.xml');
        $this->assertEquals($expected, $actual);
    }
    
    public function testPHPFilesMustBeSaved() {
        $orderModelExpected = array(
                    'data/expected/ubl2.0/oasis/names/specification/ubl/schema/xsd/Order_2/Order.php',
                    'data/expected/ubl2.0/oasis/names/specification/ubl/schema/xsd/Order_2/OrderType.php',
                    'data/expected/ubl2.0/oasis/names/specification/ubl/schema/xsd/CommonBasicComponents_2/AcceptedIndicator.php',
                    'data/expected/ubl2.0/oasis/names/specification/ubl/schema/xsd/CommonBasicComponents_2/AcceptedIndicatorType.php',
                    'data/expected/ubl2.0/oasis/names/specification/ubl/schema/xsd/CommonBasicComponents_2/AccountID.php',
                    'data/expected/ubl2.0/oasis/names/specification/ubl/schema/xsd/CommonBasicComponents_2/AccountIDType.php',
                    'data/expected/ubl2.0/oasis/names/specification/ubl/schema/xsd/CommonBasicComponents_2/AccountingCost.php'
                    );
        $orderModelActual = array(
                    'data/generated/ubl2.0/oasis/names/specification/ubl/schema/xsd/Order_2/Order.php',
                    'data/generated/ubl2.0/oasis/names/specification/ubl/schema/xsd/Order_2/OrderType.php',
                    'data/generated/ubl2.0/oasis/names/specification/ubl/schema/xsd/CommonBasicComponents_2/AcceptedIndicator.php',
                    'data/generated/ubl2.0/oasis/names/specification/ubl/schema/xsd/CommonBasicComponents_2/AcceptedIndicatorType.php',
                    'data/generated/ubl2.0/oasis/names/specification/ubl/schema/xsd/CommonBasicComponents_2/AccountID.php',
                    'data/generated/ubl2.0/oasis/names/specification/ubl/schema/xsd/CommonBasicComponents_2/AccountIDType.php',
                    'data/generated/ubl2.0/oasis/names/specification/ubl/schema/xsd/CommonBasicComponents_2/AccountingCost.php');
        
        if (file_exists(dirname(__FILE__).'/data/generated/ubl2.0')) {
            $this->rmdir_recursive(realpath('data/generated/ubl2.0'));
        }
        $this->tclass->saveClasses(dirname(__FILE__).'/data/generated/ubl2.0', true);
        
        $i = 0;
        foreach ($orderModelExpected as $model) {
            $this->assertEquals(file_get_contents($model), file_get_contents($orderModelActual[$i]));
            $i++;
        }
        
        if (file_exists('data/generated/ubl2.0')) {
           $this->rmdir_recursive(realpath('data/generated/ubl2.0'));
        }
        
    }
    
    public function testSimpleSchema1() {
        $this->tclass = new xsd2php\Xsd2Php("../resources/simple1/simple.xsd");
        $xml = $this->tclass->getXML();
        //file_put_contents(dirname(__FILE__).'/data/expected/simple1/generated.xml', $xml->saveXml());
        $expectedXml = file_get_contents(dirname(__FILE__).'/data/expected/simple1/generated.xml');
        $this->assertEquals($expectedXml, $xml->saveXml());
        
        
        if (file_exists(dirname(__FILE__).'/data/generated/simple1')) {
            $this->rmdir_recursive(realpath('data/generated/simple1'));
        }
        
        $shipModelExpected = array(
                    'data/expected/simple1/address.php',
                    'data/expected/simple1/city.php',
                    'data/expected/simple1/country.php',
                    'data/expected/simple1/item.php',
                    'data/expected/simple1/name.php',
                    'data/expected/simple1/note.php',
                    'data/expected/simple1/orderperson.php',
                    'data/expected/simple1/price.php',
                    'data/expected/simple1/quantity.php',
                    'data/expected/simple1/shiporder.php',
                    'data/expected/simple1/shipto.php',
                    'data/expected/simple1/title.php'
                    );
        $shipModelActual = array(
                    'data/generated/simple1/address.php',
                    'data/generated/simple1/city.php',
                    'data/generated/simple1/country.php',
                    'data/generated/simple1/item.php',
                    'data/generated/simple1/name.php',
                    'data/generated/simple1/note.php',
                    'data/generated/simple1/orderperson.php',
                    'data/generated/simple1/price.php',
                    'data/generated/simple1/quantity.php',
                    'data/generated/simple1/shiporder.php',
                    'data/generated/simple1/shipto.php',
                    'data/generated/simple1/title.php'
                    );
        
        
        $this->tclass->saveClasses(dirname(__FILE__).'/data/generated/simple1', true);
        
        $i = 0;
        foreach ($shipModelExpected as $model) {
            $this->assertEquals(file_get_contents($model), file_get_contents($shipModelActual[$i]));
            $i++;
        }
        
        if (file_exists(dirname(__FILE__).'/data/generated/simple1')) {
            $this->rmdir_recursive(realpath('data/generated/simple1'));
        }
    }
    
    public function testMultiLevelImportAndIncludes() {
         
         $this->tclass = new xsd2php\Xsd2Php("../resources/MultiLevelImport/ContactPerson.xsd");
         $xml = $this->tclass->getXML();
         //file_put_contents(dirname(__FILE__).'/data/expected/MultiLevelImport/generated.xml', $xml->saveXml());
         $expectedXml = file_get_contents(dirname(__FILE__).'/data/expected/MultiLevelImport/generated.xml');
         $this->assertEquals($expectedXml, $xml->saveXml());
         
         if (file_exists(dirname(__FILE__).'/data/generated/MultiLevelImport')) {
            $this->rmdir_recursive(realpath(dirname(__FILE__).'/data/generated/MultiLevelImport'));
         } 
         
         $this->tclass->saveClasses(dirname(__FILE__).'/data/generated/MultiLevelImport', true);
         
         if (file_exists(dirname(__FILE__).'/data/generated/MultiLevelImport')) {
            $this->rmdir_recursive(realpath(dirname(__FILE__).'/data/generated/MultiLevelImport'));
         } 
    }
    
    public function testContactPerson1() {
         
         $this->tclass = new xsd2php\Xsd2Php("../resources/ContactPerson1/ContactPerson.xsd");
         $xml = $this->tclass->getXML();
         //file_put_contents(dirname(__FILE__).'/data/expected/ContactPerson1/generated.xml', $xml->saveXml());
         $expectedXml = file_get_contents(dirname(__FILE__).'/data/expected/ContactPerson1/generated.xml');
         $this->assertEquals($expectedXml, $xml->saveXml());
         
         if (file_exists(dirname(__FILE__).'/data/generated/ContactPerson1')) {
            $this->rmdir_recursive(realpath(dirname(__FILE__).'/data/generated/ContactPerson1'));
         } 
         
         $this->tclass->saveClasses(dirname(__FILE__).'/data/generated/ContactPerson1', true);
         
         if (file_exists(dirname(__FILE__).'/data/generated/ContactPerson1')) {
            $this->rmdir_recursive(realpath(dirname(__FILE__).'/data/generated/ContactPerson1'));
         } 
    }
}