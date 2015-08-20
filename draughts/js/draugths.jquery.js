/*
 * Draughts
 * 
 * Version: 1.0.0
 * 
 * Copyright 2015 Galaa (www.galaa.mn). All Rights Reserved.
 * 
 * License - http://www.gnu.org/copyleft/gpl.html
 * 
 */

$draughts_speed = 1000;

function draughts($speed){

	$draughts_speed = $speed;
	$('div.draugths').each(function(){
		var $board = $(this).append($('<div/>').addClass('board')).find('.board');
		draw_board($board);
		draw_piecies($board, $(this).data('pieces'));
		draw_control($board);
	});
	$(".draughts-play").click(function(){
		draughts_play($(this));
	});
	$(".draughts-revert").click(function(){
		draughts_revert($(this));
	});

}

function draw_board($board){

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

function draw_piecies($board, $pieces){

	$.each($pieces, function($type, $piece){
		if($type === 'wp' || $type === 'wk'){
			$team = 'white';
		}else{
			$team = 'black';
		}
		$.each($piece, function($key, $position){
			$coordinat = piece_coordinat($position);
			$board.append(
				$('<div/>').addClass('piece piece-'+$position+' piece-'+$team).css({
					"position":"absolute",
					"top": $coordinat.top + "px",
					"left": $coordinat.left + "px",
				})
			);
			if($type === 'wk' || $type === 'bk'){
				$board.find('.piece-'+$position).append(
					$('<div/>').addClass('crown')
				);
			}
		});
	});

}

function piece_coordinat($square_number){

	var $x = 5;
	var $y = 5;
	if(($square_number - 1) % 10 < 5){
		$x += 35 * (($square_number - 1) % 5 * 2 + 1);
	}else{
		$x += 35 * (($square_number - 1) % 5 * 2);
	}
	$y += 35 * Math.floor(($square_number - 1) / 5);
	return {left:$x,top:$y};

}

function draw_control($board){

	var $table = $board.closest('.draugths');
	if($.isEmptyObject($table.data('moves')) === false)
	{
		$board.append(
			$('<div/>').addClass('controls')
				.append($('<div/>').addClass('control draughts-play').append($('<i/>').addClass('icon-play')))
				.append($('<div/>').addClass('control draughts-revert hidden').append($('<i/>').addClass('icon-revert')))
		);
	}

}

function draughts_play($control){
	var $table = $control.closest('.draugths');
	$table.find('.draughts-play').addClass('hidden');
	var $moves = $table.data('moves');
	$.each($moves, function($piece_number, $move){
		var $piece = $table.find('.piece-'+$piece_number);
		for(var $2nd_key in $move.moves){
			$next_position = $move.moves[$2nd_key].position;
			$coordinat = piece_coordinat($next_position);
			var $wait = $move.moves[$2nd_key].wait*$draughts_speed;
			$piece.delay($wait).animate({left:$coordinat.left+'px',top:$coordinat.top+'px'}, $draughts_speed);
		}
		if($move.crowned > 0){
			var $wait = $move.crowned * $draughts_speed;
			setTimeout(function(){
				$piece.append($('<div/>').addClass('crown'));
			}, $wait);
		}
		if($move.eaten > 0){
			var $wait = $move.eaten * $draughts_speed - Math.floor($draughts_speed / 2);
			$piece.delay($wait).fadeOut();
		}
	});
	$table.find('.draughts-revert').removeClass('hidden');
}

function draughts_revert($control){
	var $table = $control.closest('.draugths');
	var $board = $table.find('.board');
	$board.find('.piece').remove();
	draw_piecies($board, $table.data('pieces'));
	$board.find('.draughts-play').removeClass('hidden');
	$board.find('.draughts-revert').addClass('hidden');
}
