<?php

namespace AppBundle\Entity;

/**
 * RDV
 */
class RDV
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $fname;

    /**
     * @var string
     */
    private $email;

    /**
     * @var int
     */
    private $phone;

    /**
     * @var string
     */
    private $suj;

    /**
     * @var string
     */
    private $des;

    /**
     * @var \DateTime
     */
    private $rdvdate;

    /**
     * @var \DateTime
     */
    private $rdvtime;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return RDV
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set fname
     *
     * @param string $fname
     *
     * @return RDV
     */
    public function setFname($fname)
    {
        $this->fname = $fname;

        return $this;
    }

    /**
     * Get fname
     *
     * @return string
     */
    public function getFname()
    {
        return $this->fname;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return RDV
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone
     *
     * @param integer $phone
     *
     * @return RDV
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return int
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set suj
     *
     * @param string $suj
     *
     * @return RDV
     */
    public function setSuj($suj)
    {
        $this->suj = $suj;

        return $this;
    }

    /**
     * Get suj
     *
     * @return string
     */
    public function getSuj()
    {
        return $this->suj;
    }

    /**
     * Set des
     *
     * @param string $des
     *
     * @return RDV
     */
    public function setDes($des)
    {
        $this->des = $des;

        return $this;
    }

    /**
     * Get des
     *
     * @return string
     */
    public function getDes()
    {
        return $this->des;
    }

    /**
     * Set rdvdate
     *
     * @param \DateTime $rdvdate
     *
     * @return RDV
     */
    public function setRdvdate($rdvdate)
    {
        $this->rdvdate = $rdvdate;

        return $this;
    }

    /**
     * Get rdvdate
     *
     * @return \DateTime
     */
    public function getRdvdate()
    {
        return $this->rdvdate;
    }

    /**
     * Set rdvtime
     *
     * @param \DateTime $rdvtime
     *
     * @return RDV
     */
    public function setRdvtime($rdvtime)
    {
        $this->rdvtime = $rdvtime;

        return $this;
    }

    /**
     * Get rdvtime
     *
     * @return \DateTime
     */
    public function getRdvtime()
    {
        return $this->rdvtime;
    }
}

