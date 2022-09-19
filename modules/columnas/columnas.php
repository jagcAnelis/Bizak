<?php
/**
* 2007-2018 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}
use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Filter\Filter;
use Symfony\Component\Form\Extension\Core\Type\TextType;
class Columnas extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'columnas';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Alex Lozano';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Columnas Extra');
        $this->description = $this->l('Modificar las Columnas de los listados en el backoficce');



        $this->ps_versions_compliancy = [
            'min' => '1.7.6.0',
            'max' => _PS_VERSION_,
        ];
    }

    /**
     * This function is required in order to make module compatible with new translation system.
     *
     * @return bool
     */
    public function isUsingNewTranslationSystem()
    {
        return true;
    }

    /**
     * Install module and register hooks to allow grid modification.
     *
     * @see https://devdocs.prestashop.com/1.7/modules/concepts/hooks/use-hooks-on-modern-pages/
     *
     * @return bool
     */
    public function install()
    {
        return parent::install() &&
            // Register hook to allow Customer grid definition modifications.
            // Each grid's definition modification hook has it's own name. Hook name is built using
            // this structure: "action{grid_id}GridDefinitionModifier", in this case "grid_id" is "customer"
            // this means we will be modifying "Sell > Customers" page grid.
            // You can check any definition factory service in PrestaShop\PrestaShop\Core\Grid\Definition\Factory
            // to see available grid ids. Grid id is returned by `getId()` method.
            $this->registerHook('actionProductGridDefinitionModifier') &&
            // Register hook to allow Customer grid query modifications which allows to add any sql condition.
            $this->registerHook('actionProductGridQueryBuilderModifier') &&
            // Register hook to allow overriding customer form
            // this structure: "action{block_prefix}FormBuilderModifier", in this case "block_prefix" is "customer"
            // {block_prefix} is either retrieved automatically by its type. E.g "ManufacturerType" will be "manufacturer"
            // or it can be modified in form type by overriding "getBlockPrefix" function
            $this->registerHook('actionProductFormBuilderModifier') &&
            $this->registerHook('actionAfterCreateProductFormHandler') &&
            $this->registerHook('actionAfterUpdateProductFormHandler')
            ;
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    /**
     * Hook allows to modify Customers grid definition.
     * This hook is a right place to add/remove columns or actions (bulk, grid).
     *
     * @param array $params
     */
    public function hookActionProductGridDefinitionModifier(array $params)
    {
        /** @var GridDefinitionInterface $definition */
        $definition = $params['definition'];

        $translator = $this->getTranslator();
        $definition
            ->getColumns()
            ->addAfter(
                'active',
                (new DataColumn('price_with_discounts'))
                    ->setName($translator->trans('Precio con descuentos', [], 'Modules.columnas.Admin'))
                    ->setOptions([
                        'field' => 'price_with_discounts',
                    ])
            )
        ;
       $definition->getFilters()->add(
                    (new Filter('price_with_discounts', TextType::class))
                        ->setAssociatedColumn('price_with_discounts')
                        ->setTypeOptions([
                            'required' => false,
                            'attr' => [
                                'placeholder' => $this->trans('Precio con descuentos', [], 'Admin.Actions'),
                            ],
                        ])
                );


    }

    /**
     * Hook allows to modify Customers query builder and add custom sql statements.
     *
     * @param array $params
     */
    public function hookActionProductGridQueryBuilderModifier(array $params)
    {
        /** @var QueryBuilder $searchQueryBuilder */
        $searchQueryBuilder = $params['search_query_builder'];

        /** @var OrderFilters $searchCriteria */
        $searchCriteria = $params['search_criteria'];

        $searchQueryBuilder->addSelect(
            'ca.name as carrier_name,o.id_carrier'
        );

        $searchQueryBuilder->leftJoin(
            'o',
            '`' . pSQL(_DB_PREFIX_) . 'carrier`',
            'ca',
            'o.`id_carrier` = ca.`id_carrier`'
        );
        // ORIGINAL DE ALEX
        //if ('carrier_name' === $searchCriteria->getOrderBy()) {
        //    $searchQueryBuilder->orderBy('c.`carrier_name`', $searchCriteria->getOrderWay());
        //}
        //
        $orderBy = $searchCriteria->getOrderBy();
        if ('carrier_name' === $searchCriteria->getOrderBy()) {
            $searchQueryBuilder->orderBy($orderBy, $searchCriteria->getOrderWay());
        }

        foreach ($searchCriteria->getFilters() as $filterName => $filterValue) {
            if ('carrier_name' === $filterName) {
                $searchQueryBuilder->andWhere('ca.`name` LIKE "%'.$filterValue.'%"');
                $searchQueryBuilder->setParameter('ca.name', $filterValue);

                if (!$filterValue) {
                    $searchQueryBuilder->orWhere('ca.`name` IS NULL');
                }
            }
        }
    }
    /**
     * Hook allows to modify Orders form and add additional form fields as well as modify or add new data to the forms.
     *
     * @param array $params
     */
    public function hookActionProductFormBuilderModifier(array $params)
    {
        /** @var FormBuilderInterface $formBuilder */
        $formBuilder = $params['form_builder'];
        $formBuilder->add('price_with_discounts', SwitchType::class, [
            'label' => $this->getTranslator()->trans('Price with discounts', [], 'Modules.columnas.Admin'),
            'required' => false,
        ]);

        /**
         * @var CommandBusInterface
         */
        $queryBus = $this->get('prestashop.core.query_bus');

        /**
         * This part demonstrates the usage of CQRS pattern query to perform read operation from Reviewer entity.
         *
         * @see https://devdocs.prestashop.com/1.7/development/architecture/cqrs/ for more detailed information.
         *
         * As this is our recommended approach of reading the data but we not force to use this pattern in modules -
         * you can use directly an entity here or wrap it in custom service class.
         *
         * @var ReviewerSettingsForForm
         */
        $reviewerSettings = $queryBus->handle(new GetReviewerSettingsForForm($params['id']));

        $params['data']['price_with_discounts'] = $reviewerSettings->isCarrierName();

        $formBuilder->setData($params['data']);
    }

    /**
     * Hook allows to modify Orders form and add additional form fields as well as modify or add new data to the forms.
     *
     * @param array $params
     *
     * @throws OrderException
     */


    /**
     * Handles exceptions and displays message in more user friendly form.
     *
     * @param ReviewerException $exception
     *
     * @throws \PrestaShop\PrestaShop\Core\Module\Exception\ModuleErrorException
     */
    private function handleException(ReviewerException $exception)
    {
        $exceptionDictionary = [
            CannotCreateReviewerException::class => $this->getTranslator()->trans(
                'Failed to create a record for Order',
                [],
                'Modules.Columnas.Admin'
            ),
            CannotToggleAllowedToReviewStatusException::class => $this->getTranslator()->trans(
                'Failed to toggle is allowed to review status',
                [],
                'Modules.Columnas.Admin'
            ),
        ];

        $exceptionType = get_class($exception);

        if (isset($exceptionDictionary[$exceptionType])) {
            $message = $exceptionDictionary[$exceptionType];
        } else {
            $message = $this->getTranslator()->trans(
                'An unexpected error occurred. [%type% code %code%]',
                [
                    '%type%' => $exceptionType,
                    '%code%' => $exception->getCode(),
                ],
                'Admin.Notifications.Error'
            );
        }

        throw new \PrestaShop\PrestaShop\Core\Module\Exception\ModuleErrorException($message);
    }

}
