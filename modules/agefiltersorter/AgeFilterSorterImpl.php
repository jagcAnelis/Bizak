<?php

/**
 * Sorts age search filters (Catálogo > Atributos y Características > Característica > Edad) by age.
 *
 * We have filters like bellow, but unsorted:
 *
 * 0-18 meses
 * 3-5 años
 * 6-10 años
 * + 10 años
 */
class AgeFilterSorterImpl {
    static function sortAgeFiltersByAge($filters) {

        if (!self::containsAgeFilters($filters)) {
            return $filters;
        }

        $monthsFilters = array_filter($filters, [__CLASS__, 'isMonthFilter']);
        $yearsFilters = array_filter($filters, [__CLASS__, 'isYearFilter']);
        $lastFilter = array_filter($filters, [__CLASS__, 'isLastFilter']);
        return array_merge($monthsFilters, $yearsFilters, $lastFilter);
    }

    static function isMonthFilter($filter) {
        return stripos($filter["label"], "mes") !== false;
    }

    static function isYearFilter($filter) {
        return !self::isMonthFilter($filter) && !self::isLastFilter($filter);
    }

    static function isLastFilter($filter) {
        return $filter["label"][0] === "+"; // last filter is a "+ N years" upper range
    }

    static function containsAgeFilters($filters) {
        return !empty($filters) && $filters[0]["facetLabel"] === "Edad";
    }


    static function sortAgeCategoryByAge($filters) {

        $index = self::containsAgeCategory($filters);
        if (!$index) {
            return $filters;
        } else {
            $monthsFilters = array_filter($filters[$index]['children'], [__CLASS__, 'isMonthFilterCategory']);
            $yearsFilters = array_filter($filters[$index]['children'], [__CLASS__, 'isYearFilterCategory']);
            $lastFilter = array_filter($filters[$index]['children'], [__CLASS__, 'isLastFilterCategory']);

            $filters[$index]['children'] = array_merge($monthsFilters, $yearsFilters, $lastFilter);
            return $filters;
        }


    }

    static function containsAgeCategory($filters) {
        if (!empty($filters)) {
            foreach ($filters as $index => $filter) {
                if ($filter['name'] === 'Edad') {
                    return $index;
                }
            }
            return false;
        } else {
            return false;
        }
    }



    static function isMonthFilterCategory($filter) {
        return stripos($filter["name"], "mes") !== false;
    }

    static function isYearFilterCategory($filter) {
        return !self::isMonthFilterCategory($filter) && !self::isLastFilterCategory($filter);
    }

    static function isLastFilterCategory($filter) {
        return $filter["name"][0] === "+"; // last filter is a "+ N years" upper range
    }
}

