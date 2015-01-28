/*
 * Draughts - Free Version
 * 
 * Version: 1.0.0
 * 
 * Copyright 2015 Galaa (www.galaa.mn). All Rights Reserved.
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 */

function draughts($table){

	$('div.draugths').each(function(){
		draw_board($(this));
		draw_piecies($(this));
	});

}

function draw_board($table){

	var $board = $table;
	$board.append($('<div/>').addClass('board'));
	$square_number = 1;
	for(var $row = 0; $row<10; $row++){
		for(var $col = 0; $col<10; $col++){
			if($row % 2 == 1 && $col % 2 === 0 || $row % 2 == 0 && $col % 2 === 1){
				$board.append($('<div/>').addClass('square dark '+$square_number).append($('<p/>').append($square_number)));
				$square_number ++;
			}else{
				$board.append($('<div/>').addClass('square light'));
			}
		}
	}

}

function draw_piecies($table){

	var $board = $table.find('.board');
	var $pieces = $table.data('pieces');
	$.each($pieces, function($key, $piece){
		if($piece.crowned === '1'){
			$board.append($('<div/>').addClass('piece piece-'+$piece.position+' piece-'+$piece.team).append($('<div/>').addClass('crown')));
		}else{
			$board.append($('<div/>').addClass('piece piece-'+$piece.position+' piece-'+$piece.team));
		}
	});

}

$(document).ready(function(){

	draughts();

});
