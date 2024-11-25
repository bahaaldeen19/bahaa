<?php

class Review
{
    private $eventID;
    private $ticketNumber;
    private $rating;
    private $comment;
    private $username;

    public function __construct($eventID, $ticketNumber, $rating, $comment, $username)
    {
        $this->eventID = $eventID;
        $this->ticketNumber = $ticketNumber;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->username = $username;
    }

    public function getEventID()
    {
        return $this->eventID;
    }

    public function getTicketNumber()
    {
        return $this->ticketNumber;
    }

    public function getRating()
    {
        return $this->rating;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function getUsername()
    {
        return $this->username;
    }
}
