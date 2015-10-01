<?php 
/* ЗАДАЧА 1
 * 
 * Дано:
 * 	- Текст из *.csv файла
 * Необходимо:
 * 	1. Распарсить текст, подготовить данные к работе (элемент = тип Объект)
 * 	2. Отсортировать данные по дате затем КБК и вывести в таблице, таким образом, что если существует несколько записей на одну дату с одним КБК, то в поле %% считать среднее, а в скобках вывести кол-во елементов.
 * 
 *  Пример Табл.:
 *  | ДАТА       | КБК      | Адрес             | %%      |
 *  | 11.01.2013 | 1-01-001 | Спб, Восстания, 1 | 84% (2) | 
 *  
 */

$data = "
02-01-2013;1-01-001;Спб, Восстания, 1;95
05-01-2013;1-02-011;Спб, Савушкина, 106;87
01-01-2013;1-01-003;Спб, Обводный канал, 12 ;92
06-02-2013;2-05-245;Ростов-на-Дону, Стачек, 41;79
12-01-2012;5-10-002;Новосибирск, Ленина, 105;75
01-01-2013;1-01-003;Спб, Обводный канал, 12 ;98
03-01-2013;6-30-855;Сочи, Коммунистическая, 2;84
05-01-2013;2-04-015;Ростов-на-Дону, Пушкинская, 102;71
07-01-2013;6-01-010;Сочи, Приморская, 26;62
05-01-2013;1-02-011;Спб, Савушкина, 106;89
01-01-2013;1-01-003;Спб, Обводный канал, 12 ;57
";

class Shop{
	
		public $datee;
		public $kbk;
		public $address;
		public $percent;
	
		function __construct($datee, $kbk, $address, $percent){
				$this->datee = $datee;
				$this->kbk = $kbk;
				$this->address = $address;
				$this->percent = $percent;
		}
		
}

$shops = array();

$stringarr = explode("\n", $data);

foreach($stringarr as $string){
		
		if (substr_count($string, ';') == 3){
				$params = explode(';', $string);
				$shops[] = new Shop($params[0], $params[1], $params[2], $params[3]);
				
		}
}

// функция для сортировки массива объектов

function mySortDateBBK($f1,$f2)
{
		if(strtotime($f1->datee) < strtotime($f2->datee)) return -1;
		elseif(strtotime($f1->datee) > strtotime($f2->datee)) return 1;
		else {
				if($f1->kbk < $f2->kbk) return -1;
				elseif($f1->kbk > $f2->kbk) return 1;
				else return 0;
		}
}

usort($shops,"mySortDateBBK");

// Выводим отсортированные объекты

print "<table border='1'>
			</tr>
				<td>Дата</td>
				<td>КБК</td>
				<td>Адрес</td>
				<td>%%</td>
			</tr>";

foreach($shops as $singleshop){

	print "<tr>
				<td>".$singleshop->datee."</td>
				<td>".$singleshop->kbk."</td>
				<td>".$singleshop->address."</td>
				<td>".$singleshop->percent."</td>
			</tr>";

}

print "</table>";

// Выводим объекты с учетом одинаковых данных

print "<br><br><table border='1'>
			</tr>
				<td>Дата</td>
				<td>КБК</td>
				<td>Адрес</td>
				<td>%%</td>
			</tr>";

$countshops = 1;
			
for($step = 0; $step <= count($shops); $step++){

		if ($step == 0) $average_percent = $shops[$step]->percent;

		if ($step > 0 and $step < count($shops)){
				if($shops[$step]->datee == $prev_datee and $shops[$step]->kbk == $prev_kbk){
						$countshops++;
						$average_percent += $shops[$step]->percent;
				} else {
						print "<tr>
							<td>".$prev_datee."</td>
							<td>".$prev_kbk."</td>
							<td>".$prev_address."</td>
							<td>".round(($average_percent/$countshops),2);
							
						if ($countshops > 1) print "(".$countshops.")";
							
						print	"</td>
						</tr>";
						$countshops = 1;
						$average_percent = $shops[$step]->percent;
				}
		}

		if($step < count($shops)){
				$prev_datee = $shops[$step]->datee;
				$prev_kbk = $shops[$step]->kbk;
				$prev_address = $shops[$step]->address;
				$prev_percent = $shops[$step]->percent;
		} else {
				print "<tr>
					<td>".$prev_datee."</td>
					<td>".$prev_kbk."</td>
					<td>".$prev_address."</td>
					<td>".round(($average_percent/$countshops),2);
							
				if ($countshops > 1) print "(".$countshops.")";
							
				print	"</td>
				</tr>";
		}
}

print "</table>";

?>