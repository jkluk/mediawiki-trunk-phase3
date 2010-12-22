<?php

/**
 * MediaWikiUpgradeExistingDatabaseTestCase
 *
 * @file
 * @ingroup Maintenance
 * Copyright (C) 2010 Dan Nessett <dnessett@yahoo.com>
 * http://citizendium.org/
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @addtogroup Maintenance
 *
 */


require_once 'MediaWikiInstallationCommonFunction.php';

/*
 * Test Case ID   : 05 (http://www.mediawiki.org/wiki/New_installer/Test_plan)
 * Test Case Name : Install Mediawiki by updating the existing database.
 * Version        : MediaWiki 1.18alpha
*/


class MediaWikiUpgradeExistingDatabaseTestCase extends MediaWikiInstallationCommonFunction {

    function setUp(){
        parent::setUp();
    }

    // Install Mediawiki using 'MySQL' database type.
    public function testUpgradeExistingDatabase(){

        $databaseName = DB_NAME_PREFIX."_upgrade_existing";
        parent::navigateInstallPage( $databaseName );

        $this->open( "http://localhost:".PORT."/".DIRECTORY_NAME."/config/index.php" );
        $this->assertEquals( "Install", $this->getText( "//div[@id='bodyContent']/div/div/h2" ));
        $this->assertEquals( "Warning: You seem to have already installed MediaWiki and are trying to install it again.Please proceed to the next page.",
                $this->getText( "//div[@id='bodyContent']/div/div/div[2]/form/div[1]/div[2]" ));

        // 'Optionis' page
        parent::clickBackButton();

        // 'Name' page
        parent::clickBackButton();

        // 'Database settings' page
        parent::clickBackButton();

        // 'Connect to database' page
        parent::clickBackButton();
        $this->type( "mysql_wgDBname", $databaseName );
        parent::clickContinueButton();

        // 'Upgrade existing installation' page  displayed next to the 'Connect to database' page.
        $this->assertEquals( "Upgrade existing installation", $this->getText( "//div[@id='bodyContent']/div/div/h2" ));

        // Warning message displayed.
        $this->assertEquals( "There are MediaWiki tables in this database.To upgrade them to MediaWiki 1.18alpha, click Continue.",
                $this->getText( "//div[@id='bodyContent']/div/div/div[2]/form/div[1]/div[2]" ));

        parent::clickContinueButton();
        $this->assertEquals( "Upgrade existing installation",
                $this->getText( "//div[@id='bodyContent']/div/div/h2" ));

        // 'Upgrade complete.' text display
        $this->assertEquals( "Upgrade complete.You can now start using your wiki. \nIf you want to regenerate your LocalSettings.php file, click the button below. This is not recommended unless you are having problems with your wiki.",
                $this->getText( "//div[@id='bodyContent']/div/div/div[4]/form/div[1]/div[2]"));

        $this->assertTrue($this->isElementPresent( "submit-regenerate" ));
        $this->click( "submit-regenerate" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );
        $this->assertEquals( "Database settings",
                $this->getText( "//div[@id='bodyContent']/div/div/h2" ));

        // 'Database settings' page
        parent::clickContinueButton();

        // Name page
        parent::completeNamePage();

        // Options page
        parent::clickContinueButton();

        // Install page
        $this->assertEquals( "Warning: You seem to have already installed MediaWiki and are trying to install it again.Please proceed to the next page.",
                $this->getText( "//div[@id='bodyContent']/div/div/div[2]/form/div[1]/div[2]" ));
        parent::clickContinueButton();

        // complete
        $this->assertEquals( "Complete!", $this->getText( "//div[@id='bodyContent']/div/div/h2" ));
        $this->assertEquals( "Congratulations!",
                $this->getText( "//div[@id='bodyContent']/div/div/div[2]/form/div[1]/div[2]/b" ));
        $this->chooseCancelOnNextConfirmation();
        parent::restartInstallation();
    }
}