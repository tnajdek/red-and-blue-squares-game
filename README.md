#LOL - The Red and Blue Squares game.

##Description

A very simple browser game for 2 players. There are 9 cells on the screen, clicking on a cell changes its color to either red or blue depending on which player has clicked the cell. The winning condition is to color the entire board (all 9 cells) in a single color. 

A sole purpose for creating this game was to experiment with WebSockets and investigate feasability of this technology for browser-based game. 

More details can be found on http://doppnet.com/

##Requirements:

* PHP >= 5.2
* Mongo DB
* PHP sockets
* PHP mongo (pecl install mongo)

##Installation
* Place code in path served by your webserver
* By default game will use port 12345. You can modify that in files index.php and tttServer.php.
* Point your WebSockets capable browser to your webserver

##License
GNU GPL 3

This project makes use of the following open-source products that have been included in the source code for convienience:

* PHP WebSockets released on the GNU GPL v3 license (http://code.google.com/p/phpwebsocket/)
* Flash WebSocket implementation released on New BSD License (https://github.com/gimite/web-socket-js)
* jQuery and jQuery doTimeout both dual licensed under the MIT or GPL Version 2 licenses

