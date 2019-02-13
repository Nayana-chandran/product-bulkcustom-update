<?php
use Magento\Framework\App\Bootstrap;
require __DIR__ . '/app/bootstrap.php';
 
$params = $_SERVER;
$bootstrap = Bootstrap::create(BP, $params);
$obj = $bootstrap->getObjectManager();
$state = $obj->get('Magento\Framework\App\State');
$state->setAreaCode('frontend');
$product = new Product;
$product->update();
echo "All products successfully updated";

class Product{
	
	
	protected function getObjectManager(){
		return $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
	}
	
	public function getShippingReturnText(){
		$shippingReturns = "<p>
			<b>Shipping</b></p>
			<ul>
				<li>Orders take 1-2 days to process.</li>
				<li>Store uses UPS Standard Ground Shipping.</li>
				<li>Delivery takes usually&nbsp;3-5 business days.</li>
			</ul>			
			<p>&nbsp;</p>";
		return $shippingReturns;
	}
	
	protected function getProduct($id){
		$product = $this->getObjectManager()->get('Magento\Catalog\Model\Product')->load($id);
		return $product;
	}
	
	
	public function getProductCollection(){
		$objectManager = $this->getObjectManager();
		$productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
		$collection = $productCollection->load();
		// Uncomment if want product type wise custom attribute update
		//$prodCollection = $collection->addFilter('type_id','configurable');
		return $collection;
	}
	
	public function update(){
		$collection = $this->getProductCollection();
		$shippingReturns = $this->getShippingReturnText();
		foreach($collection as $prod){
			if($prod->getTypeId() == "configurable"){
				$product = $this->getProduct($prod->getId());
				$product->setStoreId(0);
				$product->setShippingReturns($shippingReturns);
				try{
					$product->save();
					echo $product->getId();
					echo " Updated successfully";
					echo "\n";
				}catch(exception $ex){
					echo $ex->getMessage();
				}
			}
		}
	}
}
?>
