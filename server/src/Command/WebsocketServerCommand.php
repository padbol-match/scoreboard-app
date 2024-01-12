<?php
namespace App\Command;

use App\Services\WebSocket\WebSocketServer;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
 
class WebsocketServerCommand extends Command
{
    protected static $defaultName = "run:websocket-server";
 
    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $port = 8001;
      $output->writeln("Starting server on port " . $port);
      
      $webSocketServer = new WebSocketServer();
      $ws = new WsServer($webSocketServer);
      $httpServer = new HttpServer($ws);
      $server = IoServer::factory($httpServer, $port);

      $output->writeln("Waiting...");
      $server->run();
      return 0;
    }
}