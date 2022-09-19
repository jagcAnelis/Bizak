<?php

require __DIR__ . "/../AgeFilterSorterImpl.php";

use PHPUnit\Framework\TestCase;


class AgeFilterSorterImplTest extends TestCase {

    public function test_filters_should_be_sorted_by_age() {
        $searchFilters = [
            ["label" => "+ 10 años", "facetLabel" => "Edad"],
            ["label" => "3-5 años", "facetLabel" => "Edad"],
            ["label" => "6-10 años", "facetLabel" => "Edad"],
            ["label" => "0-18 meses", "facetLabel" => "Edad"],
        ];

        $expected = [
            ["label" => "0-18 meses", "facetLabel" => "Edad"],
            ["label" => "3-5 años", "facetLabel" => "Edad"],
            ["label" => "6-10 años", "facetLabel" => "Edad"],
            ["label" => "+ 10 años", "facetLabel" => "Edad"],
        ];

        $actual = AgeFilterSorterImpl::sortAgeFiltersByAge($searchFilters);

        $this->assertEquals($expected, $actual);
    }

}
