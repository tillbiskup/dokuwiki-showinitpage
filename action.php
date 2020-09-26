<?php
/**
 * Dokuwiki Action Plugin "Show Init Page"
 * 
 * Show a defined Init-Page on "Access Denied" and/or Startpage
 * 
 * @author   Christian Eder <target@servus.at>
 * @author   Marcel Pennewiss <opensource@pennewiss.de>
 * @author   Till Biskup <till@till-biskup.de>
 */

if(!defined('DOKU_INC')) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');

require_once(DOKU_PLUGIN.'action.php');

class action_plugin_showinitpage extends DokuWiki_Action_Plugin {

  /**
   * return some info
   */
  function getInfo(){
    return array(
      'author' => 'STADTWERKSTATT, Marcel Pennewiss, Till Biskup',
      'email'  => 'target@servus.at,opensource@pennewiss.de, Till Biskup',
      'date'   => '2020-09-26',
      'name'   => 'Show Initpage',
      'desc'   => 'If access to a page is denied (i.e. user not logged in), redirect to a defined page.',
      'url'    => 'http://develop.servus.at/software',
    );
  }

  /**
   * Register its handlers with the dokuwiki's event controller
   */
  function register(Doku_Event_Handler $controller) {
    # TPL_CONTENT_DISPLAY is called before and after content of wikipage
    # is written to output buffer
    $controller->register_hook(
      'ACTION_HEADERS_SEND', 'AFTER', $this, 'redirect_whole_content'
    );
  }

  /**
   * Handle the event
   */ 
  function redirect_whole_content(&$event, $param) {
    global $ACT;
    global $ID;
    global $conf;

    // Redirect on:
    // Access denied + ACL = NONE + Not Searchpage + (Startpage OR not Startonly configured)
    if (($ACT === 'denied') 
        && auth_quickaclcheck($ID) == AUTH_NONE 
	&& $ACT != 'search'
	&& ($ID === $conf['start'] || $this->getConf('initpagestartonly') === 0))
    {
	$rurl=$this->getConf('initpageurl');
        send_redirect(wl($rurl,'',true));
    }
  }

}

