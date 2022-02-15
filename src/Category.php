<?php declare(strict_types=1);

namespace tsjost\Tradera;

class _Page
{
	public function __construct(
		public ?int $next_page_number = null,
		public ?string $next_page_token = null,
		public array $items = [],
	) {}
}

class Category
{
	public function __construct(
		private int $category_id,
		private HttpRequest $http = new CurlRequest(),
	) {}

	public function get_items()
	{
		$page = new _Page(next_page_number: 1);

		while ( ! is_null($page->next_page_number)) {
			$page = $this->fetch_next_page($page->next_page_number, $page->next_page_token);

			while (count($page->items) > 0) {
				yield array_shift($page->items);
			}
		}
	}

	private function fetch_next_page(int $page_number, ?string $page_token): _Page
	{
		$url = "https://www.tradera.com/category/{$this->category_id}.json?sortBy=AddedOn";
		if ($page_token) {
			$url .= "&paging=$page_token&spage=$page_number";
		}

		$data = json_decode($this->http->get($url));

		$next_page_number = null;
		$next_page_token = null;

		if ($data->pagination->pageCount > $page_number) {
			$next_page_number = $page_number + 1;

			foreach ($data->pagination->pageLinks as $link) {
				if ($link->pageIndex == $next_page_number) {
					$next_page_token = $link->paging;
					break;
				}
			}
		}

		return new _Page(
			next_page_number: $next_page_number,
			next_page_token: $next_page_token,
			items: $data->items,
		);
	}
}
