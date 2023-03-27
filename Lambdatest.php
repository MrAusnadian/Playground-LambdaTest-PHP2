<?php
    require_once('vendor/autoload.php');

    use Facebook\WebDriver\Remote\RemoteWebDriver;
    use Facebook\WebDriver\WebDriverBy;
    use PHPUnit\Framework\Assert;

    $GLOBALS['LT_USERNAME'] = getenv('LT_USERNAME');
    if(!$GLOBALS['LT_USERNAME']) $GLOBALS['LT_USERNAME'] = "************************************";

    $GLOBALS['LT_ACCESS_KEY'] = getenv('LT_ACCESS_KEY');
    if(!$GLOBALS['LT_ACCESS_KEY']) $GLOBALS['LT_ACCESS_KEY'] = "*****************************************";

    $GLOBALS['LT_BROWSER'] = getenv('LT_BROWSER');
    if(!$GLOBALS['LT_BROWSER']) $GLOBALS['LT_BROWSER'] = "chrome";

    $GLOBALS['LT_BROWSER_VERSION'] = getenv('LT_BROWSER_VERSION');
    if(!$GLOBALS['LT_BROWSER_VERSION']) $GLOBALS['LT_BROWSER_VERSION'] ="latest";

    $GLOBALS['LT_OPERATING_SYSTEM'] = getenv('LT_OPERATING_SYSTEM');
    if(!$GLOBALS['LT_OPERATING_SYSTEM']) $GLOBALS['LT_OPERATING_SYSTEM'] = "win10";

    $host= "https://". $GLOBALS['LT_USERNAME'] .":" . $GLOBALS['LT_ACCESS_KEY'] ."@hub.lambdatest.com/wd/hub";

    $result="passed";

    $capabilities = array(
    "build" => "New Build",
    "name" => "PHP Test",
    "platform" => $GLOBALS['LT_OPERATING_SYSTEM'],
    "browserName" => $GLOBALS['LT_BROWSER'],
    "version" => $GLOBALS['LT_BROWSER_VERSION'],
    "network" => true,
    "visual" => true,
    "video" => true,
    "console" => true,
    );
        
    try{
        $driver = RemoteWebDriver::create($host, $capabilities);

        $itemName = 'Yey, Lets add it to list';

        $driver->get("https://lambdatest.github.io/sample-todo-app/");
        $element1 = $driver->findElement(WebDriverBy::name("li1"));
        $element1->click();
            
        $element2 = $driver->findElement(WebDriverBy::name("li2"));
        $element2->click();
            
        $element3 = $driver->findElement(WebDriverBy::id("sampletodotext"));
        $element3->sendKeys($itemName);
            
        $element4 = $driver->findElement(WebDriverBy::id("addbutton"));			
        $element4->click();
            
        $driver->wait(10, 500)->until(function($driver) {
            $elements = $driver->findElements(WebDriverBy::cssSelector("[class='list-unstyled'] li:nth-child(6) span"));
            return count($elements) > 0;
        });
        Assert::assertEquals($driver->getTitle(),'Sample page - lambdatest.com');
    } catch(Exception $e) {
        $result="failed";
        print  "Test failed with reason ".$e->getMessage();
    }
    finally{
        // finally quit the driver
        $driver->executeScript("lambda-status=".($result == "passed" ? "passed":"failed"));
        $driver->quit();
    }

?>
