<?php

class CnaesSecundarioForm extends TPage
{
    protected $form; // registration form
    protected $datagrid; // listing
    protected $pageNavigation;

    // trait com onReload, onSearch, onDelete...
    use Adianti\Base\AdiantiStandardListTrait;
    
    public function __construct()
    {
        parent::__construct();

        $this->setDatabase('cnpj_full'); // define the database
        $this->setActiveRecord('CnaesSecundario'); // define the Active Record
        $this->addFilterField('cnpj', 'cnae_ordem', 'cnae'); //campo, operador, campo do form
        $this->setDefaultOrder('cnpj', 'asc'); // define the default order

        $this->form = new BootstrapFormBuilder(__CLASS__);
        $this->form->setFormTitle('CNAE');
        $this->form->generateAria(); // automatic aria-label


        $cnpj = new TEntry('cnpj');
        $cnae = new TEntry('cnae');
        $cnae_ordem = new TEntry('cnae_ordem');

        $this->form->addFields( [new TLabel('CNPJ')],[$cnpj]);
        $this->form->addFields( [new TLabel('cnae')],[$cnae]);
        $this->form->addFields( [new TLabel('Ordem')],[$cnae_ordem]);

        // add form actions
        $this->form->addAction('Find', new TAction([$this, 'onSearch']), 'fa:search blue');        
        $this->form->addActionLink('Clear',  new TAction([$this, 'clear']), 'fa:eraser red');

        // keep the form filled with the search data
        $this->form->setData( TSession::getValue('StandardDataGridView_filter_data') );

        // create the datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->width = '100%';
        
        // add the columns
        $col_cnpj        = new TDataGridColumn('cnpj', 'CNPJ', 'right','10%');
        $col_cnae        = new TDataGridColumn('cnae', 'CNAE', 'left','10%');
        $col_cnae_ordem  = new TDataGridColumn('cnae_ordem', 'CNAE Ordem', 'left','80%');
        
        $this->datagrid->addColumn($col_cnpj);
        $this->datagrid->addColumn($col_cnae);
        $this->datagrid->addColumn($col_cnae_ordem);

        // create the datagrid model
        $this->datagrid->createModel();

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));

        // creates the page structure using a table
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form);
        $vbox->add(TPanelGroup::pack('', $this->datagrid, $this->pageNavigation));
        
        // add the table inside the page
        parent::add($vbox);
    }

    /**
     * Clear filters
     */
    function clear()
    {
        $this->clearFilters();
        $this->onReload();
    }
}