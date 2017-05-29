<?php
declare(strict_types=1);
namespace Helhum\Typo3ConsolePhpServer\Command;

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

use Helhum\Typo3Console\Mvc\Controller\CommandController;
use Symfony\Component\Process\ProcessBuilder;

class ServerCommandController extends CommandController
{
    /**
     * Start a PHP web server for the current project
     *
     * @param string $address Alternative IP address and port
     */
    public function runCommand(string $address = '127.0.0.1:8080')
    {
        $processBuilder = new ProcessBuilder(
            [
                PHP_BINARY,
                '-S',
                $address,
                '-t',
                getenv('TYPO3_PATH_WEB'),
            ]
        );
        $processBuilder->setTimeout(null);
        $process = $processBuilder->getProcess();
        $process->disableOutput();
        $process->start();
        $this->outputLine('<info>Server is running at http://%s</info>', [$address]);
        $this->outputLine('Press Ctrl-C to quit.');
        while ($process->isRunning()) {
            sleep(1);
        }
    }
}