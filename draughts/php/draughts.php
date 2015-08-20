<?php

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

class Draughts
{

	private static $position, $error;

	function __construct()
	{

		self::$error = '';

	}

	/*
	 *
	 * name: position
	 *   Call it before moves() function
	 * @param $position
	 *   example WP:15,37,WK:36,BP:11,21,33,39,BK:50
	 *   WP: The following numbers are squares with a white man (piece)
	 *   WK: The following numbers are squares with a white king
	 *   BP: The following numbers are squares with a black man (piece)
	 *   BK: The following numbers are squares with a black king
	 *   Square number range : 1 to 50.
	 * @param $moves
	 *   example 21-27,36-6,33-28,6-44,50-46
	 *   Square number range : 1 to 50.
	 * @return string
	 *
	 */

	public static function draughts($position, $moves)
	{

		$draughts = new StdClass;
		$draughts->board = '<div class="draugths" data-pieces="'.self::position($position).'" data-moves="'.self::moves($moves).'"></div>';
		$draughts->error = self::$error;
		return $draughts;

	}

	/* * * * * * * * * * * * * * * * * * *
	 *
	 *   Private Functions
	 *
	 * * * * * * * * * * * * * * * * * * */

	/*
	 *
	 * name: position
	 *   Call it before moves() function
	 * @param $position
	 * @return JSON
	 *
	 */

	private static function position($position)
	{

		$position = strtolower($position);
		if(preg_match('/[,]{2,}/', $position) || !preg_match_all('/wp:([0-9,]+)|wk:([0-9,]+)|bp:([0-9,]+)|bk:([0-9,]+)/i', strtolower($position), $pieces))
		{
			$json = '{"wp":[],"wk":[],"bp":[],"bk":[]}';
			self::$position = null;
		}
		else
		{
			$json = array();
			foreach($pieces[0] as $piece)
			{
				$piece = explode(':', $piece);
				array_push($json, '"' . $piece[0].'":["' . str_replace(',', '","', trim($piece[1], ',')) . '"]');
			}
			$json = '{'.implode(',', $json).'}';
			self::$position = json_decode($json);
			// Checking for Position
			$white_pieces = 0;
			$black_pieces = 0;
			foreach(self::$position as $type => $pieces)
			{
				foreach($pieces as $piece)
				{
					substr($type, 0, 1) === 'w' ? $white_pieces ++ : $black_pieces ++;
					if($piece === 0 || $piece > 50 || $white_pieces > 20 || $black_pieces > 20)
					{
						$json = '{"wp":[],"wk":[],"bp":[],"bk":[]}';
						self::$position = null;
						return self::convert_json($json);
					}
				}
			}
		}
		return self::convert_json($json);

	}

	/*
	 *
	 * name: moves
	 *   Call it after position() function
	 * @param $moves
	 * @return JSON
	 *
	 */

	private static function moves($moves)
	{

		if(empty($moves))
			return '{}';
		if(is_null(self::$position)) // invalid position input
		{
			self::$error = 'Error 1: Invalid Position';
			return '{}';
		}
		// Initiliaze Board & Checking for Position
		$_board = array();
		for($x = 1; $x <= 10; $x++)
		{
			for($y = 1; $y <= 10; $y++)
			{
				$_board[$x][$y] = false;
			}
		}
		foreach(self::$position as $type => $pieces)
		{
			foreach($pieces as $piece)
			{
				$coordinat = self::coordinat($piece);
				$piece_properties = new StdClass;
				$piece_properties->piece_type = $type;
				$piece_properties->piece_number = $piece;
				$_board[$coordinat->x][$coordinat->y] = $piece_properties;
			}
		}
		// Initiliaze Pieces
		$_pieces = array();
		foreach(self::$position as $type => $pieces)
		{
			foreach($pieces as $position)
			{
				$piece = new StdClass;
				$piece->moves = array();
				$piece->crowned = (int) in_array($type, array('wk', 'bk'));
				$piece->eaten = 0;
				$_pieces[$position] = $piece;
			}
		}
		// Initiliaze Given Moves
		$_moves = explode(',', $moves);
		// Analyze Moves
		$number_of_moves = 0;
		$turn_previous = null;
		foreach($_moves as $move)
		{
			if(!preg_match('/([0-9]{1,2})-([0-9]{1,2})\??([0-9&]+)?/', $move, $tmp))
			{
				self::$error = 'Error 02: Invalid Move - Bad Move Format';
				return '{}';
			}
			$move = new StdClass;
			$move->start = sprintf('%02d', $tmp[1]);
			$move->finish = sprintf('%02d', $tmp[2]);
			if($move->start == 0 || $move->start > 50 || $move->finish == 0 || $move->finish > 50) // invalid move input
			{
				self::$error = 'Error 03: Invalid Move - Wrong Square Number';
				return '{}';
			}
			if(array_key_exists(3, $tmp))
			{
				$move->milestone = explode('&', $tmp[3]);
				foreach($move->milestone as &$milestone)
				{
					if($milestone == 0 || $milestone > 50 || isset($milestone[2])) // invalid move helping input
					{
						self::$error = 'Error 04: Invalid Move - Bad Move Helping';
						return '{}';
					}
					if(!isset($milestone[1]))
						$milestone = sprintf('%02d', $milestone);
				}
			}
			else
			{
				$move->milestone = array();
			}
			unset($tmp);
			$coordinat = new StdClass;
			$coordinat->start = self::coordinat($move->start);
			$coordinat->finish = self::coordinat($move->finish);
			// Checking for turn
			$turn_current = substr($_board[$coordinat->start->x][$coordinat->start->y]->piece_type, 0, 1);
			$game_start = true;
			for($square_number = 1; $game_start && $square_number <= 20; $square_number++)
			{
				$square_number = sprintf('%02d', $square_number);
				$square_coordinat = self::coordinat($square_number);
				if($_board[$square_coordinat->x][$square_coordinat->y] === false || substr($_board[$square_coordinat->x][$square_coordinat->y]->piece_type, 0, 1) !== 'b')
				{
					$game_start = false;
				}
			}
			for($square_number = 21; $game_start && $square_number <= 30; $square_number++)
			{
				$square_number = sprintf('%02d', $square_number);
				$square_coordinat = self::coordinat($square_number);
				if($_board[$square_coordinat->x][$square_coordinat->y] !== false)
				{
					$game_start = false;
				}
			}
			for($square_number = 31; $game_start && $square_number <= 50; $square_number++)
			{
				$square_number = sprintf('%02d', $square_number);
				$square_coordinat = self::coordinat($square_number);
				if($_board[$square_coordinat->x][$square_coordinat->y] === false || substr($_board[$square_coordinat->x][$square_coordinat->y]->piece_type, 0, 1) !== 'w')
				{
					$game_start = false;
				}
			}
			if($game_start && $turn_current !== 'w')
			{
				self::$error = 'Error 05: Invalid Move - Wrong Starting Turn';
				return '{}';
			}
			if(is_null($turn_previous)) // initiliaze turn
			{
				$turn_previous = substr($_board[$coordinat->start->x][$coordinat->start->y]->piece_type, 0, 1);
			}
			else // 2nd turns
			{
				if($turn_previous === $turn_current) // invalid move - wrong turn
				{
					self::$error = 'Error 06: Invalid Move - Wrong Turn';
					return '{}';
				}
				$turn_previous = $turn_current;
			}
			// Man or King & Normal Move
			if(self::is_normal_move($_board, $coordinat->start, $coordinat->finish))
			{
				for($square_number = 1; $square_number <= 50; $square_number++)
				{
					$square_number = sprintf('%02d', $square_number);
					$initial_position = self::coordinat($square_number);
					if($_board[$initial_position->x][$initial_position->y] === false || substr($_board[$initial_position->x][$initial_position->y]->piece_type, 0, 1) !== $turn_current || self::eats($_board, $initial_position) === false)
					{
						continue;
					}
					if($initial_position !== $coordinat->start)
					{
						self::$error = 'Error 07: Invalid Move - Capturing was skipped';
						return '{}';
					}
				}
				$move_details = new StdClass;
				$move_details->position = $move->finish;
				$move_details->wait = $number_of_moves;
				if(($count = count($_pieces[$_board[$coordinat->start->x][$coordinat->start->y]->piece_number]->moves)) > 0)
				{
					for($i = 0; $i < $count; $i++)
					{
						$move_details->wait -= $_pieces[$_board[$coordinat->start->x][$coordinat->start->y]->piece_number]->moves[$i]->wait;
					}
					$move_details->wait -= $count;
				}
				// Save to Output
				array_push($_pieces[$_board[$coordinat->start->x][$coordinat->start->y]->piece_number]->moves, $move_details);
				// Update Board
				$piece_properties = new StdClass;
				$piece_properties->piece_type = $_board[$coordinat->start->x][$coordinat->start->y]->piece_type;
				$piece_properties->piece_number = $_board[$coordinat->start->x][$coordinat->start->y]->piece_number;
				$_board[$coordinat->finish->x][$coordinat->finish->y] = $piece_properties;
				$_board[$coordinat->start->x][$coordinat->start->y] = false;
				// Count Moves
				$number_of_moves++;
				// Become King
				if($_board[$coordinat->finish->x][$coordinat->finish->y]->piece_type === 'wp' && in_array($move->finish, array(1,2,3,4,5)) || $_board[$coordinat->finish->x][$coordinat->finish->y]->piece_type === 'bp' && in_array($move->finish, array(46,47,48,49,50)))
				{
					$_board[$coordinat->finish->x][$coordinat->finish->y]->piece_type = str_replace('p', 'k', $_board[$coordinat->finish->x][$coordinat->finish->y]->piece_type);
					$_pieces[$_board[$coordinat->finish->x][$coordinat->finish->y]->piece_number]->crowned = $number_of_moves;
				}
			}
			// Man or King & Eat
			else
			{
				// Search possible eats & paths
				$branches = array();
				for($square_number = 1; $square_number <= 50; $square_number++)
				{
					$square_number = sprintf('%02d', $square_number);
					$initial_position = self::coordinat($square_number);
					if($_board[$initial_position->x][$initial_position->y] === false || substr($_board[$initial_position->x][$initial_position->y]->piece_type, 0, 1) !== $turn_current || self::eats($_board, $initial_position) === false)
						continue;
					$possible_branches = array();
					array_push($possible_branches, 'p'.$square_number);
					for($branch_id = 0; $branch_id < count($possible_branches); $branch_id++)
					{
						$branch = $possible_branches[$branch_id];
						$existing_eats = array();
						if(preg_match_all('/e([0-9]{2})/', $branch, $previous_eats)):
							foreach($previous_eats[1] as $eats)
							{
								array_push($existing_eats, self::coordinat($eats));
							}
						endif;
						$last_position = self::coordinat(substr($branch, -2));
						$board = $_board;
						$piece_properties = new StdClass;
						$piece_properties->piece_type = $board[$initial_position->x][$initial_position->y]->piece_type;
						$board[$last_position->x][$last_position->y] = $piece_properties;
						$eats = self::eats($board, $last_position, $existing_eats);
						if($last_position != $initial_position)
							$board[$initial_position->x][$initial_position->y] = false;
						while($eats !== false)
						{
							if(in_array($_board[$initial_position->x][$initial_position->y]->piece_type, array('wp', 'bp')))
							{ // Man
								$new_branch = new StdClass;
								$new_branch->x = 2 * $eats->x - $last_position->x;
								$new_branch->y = 2 * $eats->y - $last_position->y;
								array_push($possible_branches, $branch.'e'.self::coordinat2square_number($eats).'p'.self::coordinat2square_number($new_branch));
							}
							else // King
							{
								$board_temp = $board;
								$k = 1;
								$new_branch = new StdClass;
								$new_branch->x = $eats->x + ($eats->x - $last_position->x > 0 ? 1 : -1) * $k;
								$new_branch->y = $eats->y + ($eats->y - $last_position->y > 0 ? 1 : -1) * $k;
								while(isset($board_temp[$new_branch->x][$new_branch->y]) && $board_temp[$new_branch->x][$new_branch->y] === false)
								{
									array_push($possible_branches, $branch.'e'.self::coordinat2square_number($eats).'p'.self::coordinat2square_number($new_branch));
									$k++;
									$new_branch = new StdClass;
									$new_branch->x = $eats->x + ($eats->x - $last_position->x > 0 ? 1 : -1) * $k;
									$new_branch->y = $eats->y + ($eats->y - $last_position->y > 0 ? 1 : -1) * $k;
								}
							}
							array_push($existing_eats, $eats);
							$eats = self::eats($board, $last_position, $existing_eats);
						}
					}
					// Search longest path
					$max_lenght = max(array_map('strlen', $possible_branches));
					foreach($possible_branches as $key => $branch)
					{
						if(strlen($branch) < $max_lenght):
							unset($possible_branches[$key]);
							continue;
						endif;
						array_push($branches, $branch);
					}
				}
				// Search correct path
				foreach($branches as $key => $branch)
				{
					if(strpos($branch, 'p'.$move->start) === false):
						unset($branches[$key]);
					endif;
					foreach($move->milestone as $milestone)
					{
						if(strpos($branch, 'p'.$milestone) === false):
							unset($branches[$key]);
							break;
						endif;
					}
					if(strpos($branch, 'p'.$move->finish) === false):
						unset($branches[$key]);
					endif;
				}
				if(count($branches) !== 1) // irregular move
				{
					self::$error = 'Error 08: Invalid Move - Wrong Capturing';
					return '{}';
				}
				$branches = str_split(implode($branches), 3);
				// Move
				foreach($branches as $key => $branch)
				{
					if($key === 0): // starting position
						continue;
					endif;
					if(substr($branch, 0, 1) === 'p') // move
					{
						$move_details = new StdClass;
						$move_details->position = substr($branch, 1);
						if($key === 2) // first move
						{
							$move_details->wait = $number_of_moves;
							if(($count = count($_pieces[$_board[$coordinat->start->x][$coordinat->start->y]->piece_number]->moves)) > 0)
							{
								for($i = 0; $i < $count; $i++)
								{
									$move_details->wait -= $_pieces[$_board[$coordinat->start->x][$coordinat->start->y]->piece_number]->moves[$i]->wait;
								}
								$move_details->wait -= $count;
							}
						}
						else // 2nd moves
						{
							$move_details->wait = 0;
						}
						// Save to Output
						array_push($_pieces[$_board[$coordinat->start->x][$coordinat->start->y]->piece_number]->moves, $move_details);
						// Count Moves
						$number_of_moves++;
					}
				}
				// Eat
				foreach($branches as $key => $branch)
				{
					if(substr($branch, 0, 1) === 'e') // eat
					{
						// Save to Output
						$eat_coordinat = self::coordinat(substr($branch, 1));
						$eaten = $number_of_moves;
						if(($count = count($_pieces[$_board[$eat_coordinat->x][$eat_coordinat->y]->piece_number]->moves)) > 0)
						{
							for($i = 0; $i < $count; $i++)
							{
								$eaten -= $_pieces[$_board[$eat_coordinat->x][$eat_coordinat->y]->piece_number]->moves[$i]->wait;
							}
							$eaten -= $count;
						}
						$_pieces[$_board[$eat_coordinat->x][$eat_coordinat->y]->piece_number]->eaten = $eaten;
						// Update board
						$_board[$eat_coordinat->x][$eat_coordinat->y] = false;
					}
				}
				// Update Board
				$piece_properties = new StdClass;
				$piece_properties->piece_type = $_board[$coordinat->start->x][$coordinat->start->y]->piece_type;
				$piece_properties->piece_number = $_board[$coordinat->start->x][$coordinat->start->y]->piece_number;
				$_board[$coordinat->finish->x][$coordinat->finish->y] = $piece_properties;
				$_board[$coordinat->start->x][$coordinat->start->y] = false;
				// Become King
				if($_board[$coordinat->finish->x][$coordinat->finish->y]->piece_type === 'wp' && in_array($move->finish, array(1,2,3,4,5)) || $_board[$coordinat->finish->x][$coordinat->finish->y]->piece_type === 'bp' && in_array($move->finish, array(46,47,48,49,50)))
				{
					$_board[$coordinat->finish->x][$coordinat->finish->y]->piece_type = str_replace('p', 'k', $_board[$coordinat->finish->x][$coordinat->finish->y]->piece_type);
					$_pieces[$_board[$coordinat->finish->x][$coordinat->finish->y]->piece_number]->crowned = $number_of_moves;
				}
			}
		}
		return self::convert_json(json_encode($_pieces));

	}

	/*
	 *
	 * name: eats
	 * @param $_board, $coordinat, $existing_eats
	 * @return Returns coordinat, if exists eating, false otherwise 
	 *
	 */

	private static function eats($_board, $coordinat, $existing_eats = array())
	{

		$piece_properties = new StdClass;
		$piece_properties->piece_type = $_board[$coordinat->x][$coordinat->y]->piece_type;
		// for Man
		if(in_array($piece_properties->piece_type, array('wp', 'bp')))
		{
			$enemies = ($piece_properties->piece_type === 'wp' ? array('bp', 'bk') : array('wp', 'wk'));
			foreach(array(1, -1) as $x)
			{
				foreach(array(1, -1) as $y)
				{
					if(isset($_board[$coordinat->x + $x][$coordinat->y + $y]) && isset($_board[$coordinat->x + $x][$coordinat->y + $y]->piece_type) && in_array($_board[$coordinat->x + $x][$coordinat->y + $y]->piece_type, $enemies) && isset($_board[$coordinat->x + 2 * $x][$coordinat->y + 2 * $y]) && is_bool($_board[$coordinat->x + 2 * $x][$coordinat->y + 2 * $y]))
					{
						$eats = new StdClass;
						$eats->x = $coordinat->x + $x;
						$eats->y = $coordinat->y + $y;
						if(!in_array($eats, $existing_eats))
						{
							return $eats;
						}
					}
				}
			}
		}
		// for King
		if(in_array($piece_properties->piece_type, array('wk', 'bk')))
		{
			$enemies = ($piece_properties->piece_type === 'wk' ? array('bp', 'bk') : array('wp', 'wk'));
			$directions = array(array(1,1), array(1,-1), array(-1,1), array(-1,-1));
			foreach($directions as &$direction)
			{
				for($i = 1; $i <= 8; $i++)
				{
					$x = $direction[0] * $i;
					$y = $direction[1] * $i;
					if(isset($_board[$coordinat->x + $x][$coordinat->y + $y]) && isset($_board[$coordinat->x + $x][$coordinat->y + $y]->piece_type) && !in_array($_board[$coordinat->x + $x][$coordinat->y + $y]->piece_type, $enemies))
					{
						unset($direction);
						$i = 9;
					}
					elseif(isset($_board[$coordinat->x + $x][$coordinat->y + $y]) && isset($_board[$coordinat->x + $x][$coordinat->y + $y]->piece_type) && in_array($_board[$coordinat->x + $x][$coordinat->y + $y]->piece_type, $enemies) && (!isset($_board[$coordinat->x + $x + $direction[0]][$coordinat->y + $y + $direction[1]]) || !is_bool($_board[$coordinat->x + $x + $direction[0]][$coordinat->y + $y + $direction[1]])))
					{
						unset($direction);
						$i = 9;
					}
					elseif(isset($_board[$coordinat->x + $x][$coordinat->y + $y]) && isset($_board[$coordinat->x + $x][$coordinat->y + $y]->piece_type) && in_array($_board[$coordinat->x + $x][$coordinat->y + $y]->piece_type, $enemies) && isset($_board[$coordinat->x + $x + $direction[0]][$coordinat->y + $y + $direction[1]]) && is_bool($_board[$coordinat->x + $x + $direction[0]][$coordinat->y + $y + $direction[1]]))
					{
						$eats = new StdClass;
						$eats->x = $coordinat->x + $x;
						$eats->y = $coordinat->y + $y;
						if(!in_array($eats, $existing_eats))
						{
							return $eats;
						}
					}
				}
			}
		}
		return false;

	}

	/*
	 *
	 * name: is_normal_move
	 * @param $_board, $start, $finish
	 * @return Returns true, if move is valid, false otherwise 
	 *
	 */

	private static function is_normal_move($_board, $start, $finish)
	{

		if(in_array($_board[$start->x][$start->y]->piece_type, array('wp', 'bp')))
		{
			if(self::eats($_board, $start) !== false) // eat
				return false;
			if(abs($start->x - $finish->x) != 1 || abs($start->y - $finish->y) != 1) // invalid move input
				return false;
			if($_board[$start->x][$start->y]->piece_type === 'wp' && $start->y < $finish->y || $_board[$start->x][$start->y]->piece_type === 'bp' && $start->y > $finish->y) // invalid move input - man backward
				return false;
		}
		elseif(in_array($_board[$start->x][$start->y]->piece_type, array('wk', 'bk')))
		{
			if(self::eats($_board, $start) !== false) // eat
				return false;
			if(abs($start->x - $finish->x) != abs($start->y - $finish->y)) // invalid move input - no diagonal
				return false;
		}
		else
		{
			return false;
		}
		return true;

	}

	/*
	 *
	 * name: coordinat
	 * @param $square_number
	 * @return Returns coordinat of given square
	 *
	 */

	private static function coordinat($square_number){

		$coordinat = new StdClass;
		$coordinat->x = 1;
		$coordinat->y = 1;
		if(($square_number - 1) % 10 < 5){
			$coordinat->x += (($square_number - 1) % 5 * 2 + 1);
		}else{
			$coordinat->x += (($square_number - 1) % 5 * 2);
		}
		$coordinat->y += floor(($square_number - 1) / 5);
		return $coordinat;

	}

	/*
	 *
	 * name: coordinat2square_number
	 * @param $coordinat
	 * @return Returns square number related to given coordinat
	 *
	 */

	private static function coordinat2square_number($coordinat){

		$square_number = 0;
		$square_number += ($coordinat->y - 1) * 5;
		if($coordinat->x % 2 === 0)
		{
			$square_number += $coordinat->x / 2;
		}
		else
		{
			$square_number += ($coordinat->x + 1) / 2;
		}
		return sprintf('%02d', $square_number);

	}

	/*
	 *
	 * name: convert_json
	 * @param $json - JSON
	 * @return JSON
	 *
	 */

	private static function convert_json($json)
	{

		return htmlspecialchars($json, ENT_QUOTES);

	}

}

?>
