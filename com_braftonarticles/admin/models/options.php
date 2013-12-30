<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
jimport('joomla.error.error');
/**
 * BraftonArticlesOptions Model
 */
class BraftonArticlesModelOptions extends JModelList
{
	protected $optionsTable;
	protected $authorTable;
	
	function __construct() {
		JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components'.'/com_braftonarticles'.'/tables');
		$this->optionsTable = $this->getTable('braftonoptions');		
		parent::__construct();
	}
	


	function setdatabase($value,$option){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
 
// Fields to update.
$fieldsapi = array(
    $db->quoteName('value').'=\''.$value.'\'',
);
 
// Conditions for which records should be updated.
$conditionsapi = array(
    $db->quoteName('option').'=\''.$option.'\'',

);
 
$query->update($db->quoteName('#__brafton_options'))->set($fieldsapi)->where($conditionsapi);
$db->setQuery($query);
$result = $db->query();


	}



	// This sets the options in the DB
	// Called from the options sub-controller
	function setOptions() {
		
		$API_pattern = "[a-zA-Z0-9]{8}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{12}";
		$baseURL_pattern = "^(http:\/\/)?api\.[^.]*\.(com|com\.au|co\.uk)\/";
		$options = JRequest::get('post');
		
		if(!preg_match('/'.$API_pattern.'/', $options['api-key'], $apiKey)) {
			JError::raiseWarning(100, 'There was a problem registering your API key.  Please double check and try again.');
			return;
		}
		if(!preg_match('/'.$baseURL_pattern.'/', $options['base-url'], $baseURL)){
			JError::raiseWarning(100, 'There was a problem registering your base URL.  Please double check and try again.');
			return;
		}


$this->setdatabase($options['api-key'],'api-key');
$this->setdatabase($options['base-url'],'base-url');
$this->setdatabase($options['author'],'author');
$this->setdatabase($options['import-order'],'import-order');
$this->setdatabase($options['published-state'],'published-state');
$this->setdatabase($options['update-articles'],'update-articles');
$this->setdatabase($options['parent-category'],'parent-category');

 
// Fields to update.






JFactory::getApplication()->enqueueMessage('Your options have successfully been saved.  Please note that your articles will not import until you have activated the <a href="index.php?option=com_plugins">bundled cron plugin</a>.');


}


	/* getAPIKey()
	 * Pre - N/A
	 * Post - returns API Key, string
	 */ 
	function getAPIKey() {
		$this->optionsTable->load('api-key');
		return $this->optionsTable->value;
	}
	
	/* getBaseURL()
	 * Pre - N/A
	 * Post - returns base URL, string
	 */
	function getBaseURL () {
		$this->optionsTable->load('base-url');
		return $this->optionsTable->value;
	}
	
	/* getAuthor()
	 * Pre - N/A
	 * Post - returns author, string
	 */
	function getAuthor() {
		$this->optionsTable->load('author');
		return $this->optionsTable->value;
	}
	
	function getAuthorList() {
		$db = JFactory::getDBO();
		$query = "SELECT name, id FROM #__users";
		$db->setQuery($query);
		$authors = $db->loadObjectList();
		return $authors;
	}
	
	function getImportOrder() {
		$this->optionsTable->load('import-order');
		return $this->optionsTable->value;
	}
	
	function getPublishedState() {
		$this->optionsTable->load('published-state');
		return $this->optionsTable->value;
	}
	
	function getUpdateArticles() {
		$this->optionsTable->load('update-articles');
		return $this->optionsTable->value;
	}
	
	function getParentCategory() {
		$this->optionsTable->load('parent-category');
		return $this->optionsTable->value;
	}
	
} // end class
?>