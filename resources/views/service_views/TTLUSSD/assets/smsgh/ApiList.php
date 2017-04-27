<?php

class ApiList {

    private $count;
    private $totalPages;
    private $items;

    /**
     * Primary constructor.
     */
    public function __construct($json) {
        if ($json instanceof stdClass) {
            $this->items = array();

            foreach ($json as $name => $value)
                switch (strtolower($name)) {
                    case 'count':
                        $this->count = $value;
                        break;

                    case 'totalpages':
                        $this->totalPages = $value;
                        break;

                    case 'actionlist':
                        foreach ($value as $o)
                            $this->items[] = new Action($o);
                        break;

                    case 'campaignlist':
                        foreach ($value as $o)
                            $this->items[] = new Campaign($o);
                        break;

                    /* case 'childaccountlist':
                      foreach ($value as $o)
                      $this->items[] = new Smsgh_ApiChildAccount($o);
                      break;
                     */
                    case 'contactlist':
                        foreach ($value as $o)
                            $this->items[] = new Contact($o);
                        break;

                    case 'grouplist':
                        foreach ($value as $o)
                            $this->items[] = new ContactGroup($o);
                        break;

                    case 'invoicestatementlist':
                        foreach ($value as $o)
                            $this->items[] = new Invoice($o);
                        break;

                    case 'messages':
                        foreach ($value as $o)
                            $this->items[] = new Message($o);
                        break;

                    case 'messagetemplatelist':
                        foreach ($value as $o)
                            $this->items[] = new MessageTemplate($o);
                        break;

                    case 'mokeywordlist':
                        foreach ($value as $o)
                            $this->items[] = new MoKeyWord($o);
                        break;

                    case 'numberplanlist':
                        foreach ($value as $o)
                            $this->items[] = new NumberPlan($o);
                        break;

                    case 'senderaddresseslist':
                        foreach ($value as $o)
                            $this->items[] = new Sender($o);
                        break;

                    case 'servicelist':
                        foreach ($value as $o)
                            $this->items[] = new Service($o);
                        break;
                    case 'ticketlist':
                        foreach ($value as $o)
                            $this->items[] = new Ticket($o);
                        break;
                    case "libraries":
                        foreach ($value as $o)
                            $this->items[] = new ContentLibrary($o);
                        break;
                    case "folders":
                        foreach ($value as $o)
                            $this->items[] = new ContentFolder($o);
                        break;
                    case "medias":
                        foreach ($value as $o)
                            $this->items[] = new ContentMedia($o);
                        break;
                }
        } else {
            throw new Exception('Bad ApiList parameter');
        }
    }

    /**
     * Gets count.
     */
    public function getCount() {
        return $this->count;
    }

    /**
     * Gets totalPages.
     */
    public function getTotalPages() {
        return $this->totalPages;
    }

    /**
     * Gets items.
     */
    public function getItems() {
        return $this->items;
    }

}
