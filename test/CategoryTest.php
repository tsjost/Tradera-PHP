<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use tsjost\Tradera;

class CategoryTest extends TestCase
{
	public function testStuff()
	{
		$items = [
			(object) ['itemId' => 1], (object) ['itemId' => 2], (object) ['itemId' => 3],
			(object) ['itemId' => 4], (object) ['itemId' => 5], (object) ['itemId' => 6],
			(object) ['itemId' => 7], (object) ['itemId' => 8], (object) ['itemId' => 9],
		];

		$http = $this->createMock(Tradera\HttpRequest::class);
		$http->expects($this->exactly(3))
			->method('get')
			->withConsecutive(
				[$this->equalTo('https://www.tradera.com/category/1337.json?sortBy=AddedOn')],
				[$this->equalTo('https://www.tradera.com/category/1337.json?sortBy=AddedOn&paging=token2&spage=2')],
				[$this->equalTo('https://www.tradera.com/category/1337.json?sortBy=AddedOn&paging=token3&spage=3')],
			)
			->willReturnOnConsecutiveCalls(
				$this->returnValue(json_encode(['items' => array_slice($items, 0, 3), 'pagination' => ['pageCount' => 3, 'pageLinks' => [
					['pageIndex' => 1, 'state' => 'active'], ['pageIndex' => 2, 'paging' => 'token2'], ['pageIndex' => 3, 'paging' => 'token3'],
				]]])),
				$this->returnValue(json_encode(['items' => array_slice($items, 3, 3), 'pagination' => ['pageCount' => 3, 'pageLinks' => [
					['pageIndex' => 1], ['pageIndex' => 2, 'paging' => 'token2', 'state' => 'active'], ['pageIndex' => 3, 'paging' => 'token3'],
				]]])),
				$this->returnValue(json_encode(['items' => array_slice($items, 6, 3), 'pagination' => ['pageCount' => 3, 'pageLinks' => [
					['pageIndex' => 1], ['pageIndex' => 2, 'paging' => 'token2'], ['pageIndex' => 3, 'paging' => 'token3', 'state' => 'active'],
				]]])),
			);

		$C = new Tradera\Category(1337, $http);

		$i = 0;
		foreach ($C->get_items() as $item) {
			$this->assertEquals($items[$i], $item);
			++$i;
		}
	}
}
