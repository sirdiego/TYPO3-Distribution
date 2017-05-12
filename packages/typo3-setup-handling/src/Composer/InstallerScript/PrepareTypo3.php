<?php
namespace Helhum\TYPO3\SetupHandling\Composer\InstallerScript;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2017 Helmut Hummel <info@helhum.io>
 *  All rights reserved
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  A copy is found in the text file GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use Composer\Script\Event as ScriptEvent;
use Helhum\Typo3Console\Mvc\Cli\CommandDispatcher;
use Helhum\Typo3ConsolePlugin\InstallerScriptInterface;

class PrepareTypo3 implements InstallerScriptInterface
{
    /**
     * @param ScriptEvent $event
     * @return bool
     */
    public function shouldRun(ScriptEvent $event)
    {
        $command = $_SERVER['argv'][1];
        return $command !== 'create-project';
    }

    /**
     * Call the TYPO3 setup
     *
     * @param ScriptEvent $event
     * @throws \RuntimeException
     * @return bool
     * @internal
     */
    public function run(ScriptEvent $event)
    {
        $composerConfig = $event->getComposer()->getConfig();
        $commandDispatcher = CommandDispatcher::createFromComposerRun([$composerConfig->get('bin-dir'), realpath('./Scripts')]);
        $commandDispatcher->executeCommand('install:generatepackagestates');
        $commandDispatcher->executeCommand('install:fixfolderstructure');
        $commandDispatcher->executeCommand('install:extensionsetupifpossible');

        return true;
    }
}