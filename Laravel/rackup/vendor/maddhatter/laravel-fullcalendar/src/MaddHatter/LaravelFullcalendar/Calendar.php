<?php namespace MaddHatter\LaravelFullcalendar;

use ArrayAccess;
use Carbon\Carbon;
use DateTime;
use Illuminate\View\Factory;
use PhpParser\Node\Scalar\String_;

class Calendar
{

    public $flag =0;
    public $defaultDate1;
    /**
     * @var Factory
     */
    protected $view;

    /**
     * @var EventCollection
     */
    protected $eventCollection;

    /**
     * @var string
     */
    protected $id;

    /**
     * Default options array
     *
     * @var array
     */
    protected $defaultOptions = [
        'header' => [
            'left' => 'prev,next today',
            'center' => 'title',
            'right' => 'month,agendaWeek,agendaDay',
        ],
        'eventLimit' => true,
        'defaultDate'
    ];

    /**
     * User defined options
     *
     * @var array
     */
    protected $userOptions = [];

    /**
     * User defined callback options
     *
     * @var array
     */
    protected $callbacks = [];

    /**
     * @param Factory         $view
     * @param EventCollection $eventCollection
     */
    public function __construct(Factory $view, EventCollection $eventCollection)
    {
        $this->view            = $view;
        $this->eventCollection = $eventCollection;
    }

    /**
     * Create an event DTO to add to a calendar
     *
     * @param string          $title
     * @param string          $isAllDay
     * @param string|DateTime $start If string, must be valid datetime format: http://bit.ly/1z7QWbg
     * @param string|DateTime $end   If string, must be valid datetime format: http://bit.ly/1z7QWbg
     * @param string          $id    event Id
     * @param array           $options
     * @return SimpleEvent
     */
    public static function event($title, $isAllDay, $start, $end, $id = null, $options = [])
    {
        return new SimpleEvent($title, $isAllDay, $start, $end, $id, $options);
    }

    /**
     * Create the <div> the calendar will be rendered into
     *
     * @return string
     */
//    public function calendar()
//    {
//        return '<div id="calendar-' . $this->getId() . '"></div>';
//
//    }

    public function calendar($month,$year){
        if ($month==null || $year==null){
            return '<div id="calendar-' . $this->getId() . '"></div>';
        }
        else{
            $this->flag =1;
            $this->getOptionsJson(array($month),array($year));
            return '<div id="calendar-' . $this->getId() . '"></div>';
        }
        
    }




    /**
     * Get the <script> block to render the calendar (as a View)
     *
     * @return \Illuminate\View\View
     */
    public function script()
    {
        $month = null;
        $year = null;
        $options = $this->getOptionsJson(array($month),array($year));


        return $this->view->make('fullcalendar::script', [
            'id' => $this->getId(),
            'options' => $options,
        ]);
    }

    /**
     * Customize the ID of the generated <div>
     *
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the ID of the generated <div>
     * This value is randomized unless a custom value was set via setId
     *
     * @return string
     */
    public function getId()
    {
        if ( ! empty($this->id)) {
            return $this->id;
        }

        $this->id = str_random(8);

        return $this->id;
    }

    /**
     * Add an event
     *
     * @param Event $event
     * @param array $customAttributes
     * @return $this
     */
    public function addEvent(Event $event, array $customAttributes = [])
    {
        $this->eventCollection->push($event, $customAttributes);

        return $this;
    }

    /**
     * Add multiple events
     *
     * @param array|ArrayAccess $events
     * @param array $customAttributes
     * @return $this
     */
    public function addEvents($events, array $customAttributes = [])
    {
        foreach ($events as $event) {
            $this->eventCollection->push($event, $customAttributes);
        }

        return $this;
    }

    /**
     * Set fullcalendar options
     *
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->userOptions = $options;

        return $this;
    }



    /**
     * Get the fullcalendar options (not including the events list)
     *
     * @return array
     */
    public function getOptions()
    {
        return array_merge($this->defaultOptions, $this->userOptions);
    }

    /**
     * Set fullcalendar callback options
     *
     * @param array $callbacks
     * @return $this
     */
    public function setCallbacks(array $callbacks)
    {
        $this->callbacks = $callbacks;

        return $this;
    }

    /**
     * Get the callbacks currently defined
     *
     * @return array
     */
    public function getCallbacks()
    {
        return $this->callbacks;
    }

    /**
     * Get options+events JSON
     *
     * @return string
     */
    public function getOptionsJson(array $month,array $year)
    {
        
        $options      = $this->getOptions();
        $placeholders = $this->getCallbackPlaceholders();
        $parameters   = array_merge($options, $placeholders);
        $parameters['timezone']="00:00+05:30";
        if ($month != null && $year != null) {
           if ($month[0] && $year[0]){
                $this->defaultDate1 =$year[0].'-'.$month[0].'-02';
//                $parameters['defaultDate'] = '2017-01-01';
            }

            $parameters['defaultDate'] =  $this->defaultDate1;

        }       
//        $parameters['defaultDate'] = '2017-01-01';
        //$parameters['defaultDate'] = $defaultDate;
        $parameters['events'] = $this->eventCollection->toArray();

        $json = json_encode($parameters);

        if ($placeholders) {
            return $this->replaceCallbackPlaceholders($json, $placeholders);
        }

        return $json;

    }

    /**
     * Generate placeholders for callbacks, will be replaced after JSON encoding
     *
     * @return array
     */
    protected function getCallbackPlaceholders()
    {
        $callbacks    = $this->getCallbacks();
        $placeholders = [];

        foreach ($callbacks as $name => $callback) {
            $placeholders[$name] = '[' . md5($callback) . ']';
        }

        return $placeholders;
    }

    /**
     * Replace placeholders with non-JSON encoded values
     *
     * @param $json
     * @param $placeholders
     * @return string
     */
    protected function replaceCallbackPlaceholders($json, $placeholders)
    {
        $search  = [];
        $replace = [];

        foreach ($placeholders as $name => $placeholder) {
            $search[]  = '"' . $placeholder . '"';
            $replace[] = $this->getCallbacks()[$name];
        }

        return str_replace($search, $replace, $json);
    }

}