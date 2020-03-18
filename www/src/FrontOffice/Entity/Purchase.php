<?php


namespace FrontOffice\Entity;

use Core\Entity\Address;
use Core\Entity\Traits\DatesAt;
use Core\Entity\Traits\Id;
use Core\Entity\Transaction;
use Core\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use FrontOffice\Model\Shipment;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Purchase.
 *
 * @ORM\Table(name="purchase")
 * @ORM\Entity(repositoryClass="FrontOffice\Repository\PurchaseRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Purchase
{
    const STATUSES = ['processing', 'shipped'];
    
    use Id;
    use DatesAt;

    /**
     * The Unique id of the purchase.
     *
     * @var                     string
     * @ORM\Column(type="guid")
     */
    protected $guid = null;

    /**
     * The date of the delivery (it doesn't include the time).
     *
     * @var                     \DateTime
     * @ORM\Column(type="date")
     */
    protected $deliveryDate = null;
    
    /**
     * The shopping information.
     *
     * @var                       Shipment
     * @ORM\Column(type="object")
     */
    protected $shipping = null;

    /**
     * The customer preferred time of the day for the delivery.
     *
     * @var                     \DateTime|null
     * @ORM\Column(type="time", nullable=true)
     */
    protected $deliveryHour = null;
    
    /**
     * The customer shopping address.
     *
     * @var                                              string
     * @ORM\OneToOne(targetEntity="Core\Entity\Address", mappedBy="purchaseShipping", cascade={"persist"})
     */
    private $shippingAddress;

    /**
     * The user who made the purchase.
     *
     * @var                                            \Core\Entity\User
     * @ORM\ManyToOne(targetEntity="Core\Entity\User", inversedBy="purchases", cascade={"persist"})
     */
    private $buyer;

    /**
     * Items that have been purchased.
     *
     * @var PurchaseItem[]
     * @ORM\OneToMany(targetEntity="PurchaseItem", mappedBy="purchase", cascade={"persist"})
     */
    protected $purchasedItems;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $trackingNumber;
    
    /**
     * @ORM\OneToOne(targetEntity="Core\Entity\Transaction", mappedBy="purchase", cascade={"persist", "remove"})
     */
    private $transaction;
    
    /**
     * @ORM\Column(name="status")
     */
    private $status;

    /**
     * Constructor of the Purchase class.
     * (Initialize some fields).
     */
    public function __construct()
    {
        $this->id = $this->generateId();
        $this->purchasedItems = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->deliveryDate = new \DateTime('+2 days');
        $this->deliveryHour = new \DateTime('14:00');
    }
    
    /**
     * @param \Core\Entity\User $buyer
     */
    public function setBuyer(User $buyer)
    {
        $this->buyer = $buyer;
        
        return $this;
    }

    /**
     * @return \Core\Entity\User
     */
    public function getBuyer(): ?User
    {
        return $this->buyer;
    }

    /**
     * @param \DateTime $deliveryDate
     */
    public function setDeliveryDate($deliveryDate)
    {
        $this->deliveryDate = $deliveryDate;
    }

    /**
     * @return \DateTime
     */
    public function getDeliveryDate()
    {
        return $this->deliveryDate;
    }

    /**
     * @param PurchaseItem[] $purchasedItems
     */
    public function setPurchasedItems($purchasedItems)
    {
        foreach ($purchasedItems as $item) {
            $this->addPurchasedItem($item);
        }
    }
    
    /**
     * @param PurchaseItem $purchasedItems
     */
    public function addPurchasedItem(PurchaseItem $purchasedItem)
    {

        if ($this->purchasedItems->contains($purchasedItem)) {
            return;
        }
        $this->purchasedItems->add($purchasedItem);
        $purchasedItem->setPurchase($this);
    }
    
    public function removePurchasedItem(PurchaseItem $purchaseItem): self
    {
        if ($this->purchasedItems->contains($purchaseItem)) {
            $this->purchasedItems->removeElement($purchaseItem);
            // set the owning side to null (unless already changed)
            if ($purchaseItem->getPurchase() === $this) {
                $purchaseItem->setPurchase(null);
            }
        }
        
        return $this;
    }

    /**
     * @return PurchaseItem[]
     */
    public function getPurchasedItems()
    {
        return $this->purchasedItems;
    }
    
    public function getTransaction(): ?Transaction
    {
        return $this->transaction;
    }
    
    public function setTransaction(Transaction $transaction): self
    {
        $this->transaction = $transaction;
        
        // set the owning side of the relation if necessary
        if ($this !== $transaction->getPurchase()) {
            $transaction->setPurchase($this);
        }
        
        return $this;
    }

    /**
     * @param \DateTime $deliveryHour
     */
    public function setDeliveryHour($deliveryHour)
    {
        $this->deliveryHour = $deliveryHour;
    }

    /**
     * @return \DateTime|null
     */
    public function getDeliveryHour()
    {
        return $this->deliveryHour;
    }

    /**
     * @param Shipment $shipping
     */
    public function setShipping($shipping)
    {
        $this->shipping = $shipping;
    }

    /**
     * @return Shipment
     */
    public function getShipping()
    {
        return $this->shipping;
    }
    
    /**
     * @return mixed
     */
    public function getTrackingNumber()
    {
        return $this->trackingNumber;
    }
    
    /**
     * @param  mixed $trackingNumber
     * @return Purchase
     */
    public function setTrackingNumber($trackingNumber)
    {
        $this->trackingNumber = $trackingNumber;
        
        return $this;
    }

    /**
     * @param int $storeId
     *
     * @return string
     */
    public function generateId($storeId = 1)
    {
        return preg_replace('/[^0-9]/i', '', sprintf('%d%d%03d%s', $storeId, date('Y'), date('z'), microtime()));
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return 'Purchase #'.$this->getId();
    }

    /**
     * @return string
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * @param string $guid
     *
     * @return Purchase
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;

        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * @param  mixed $status
     * @return Purchase
     */
    public function setStatus($status)
    {
        $this->status = $status;
        
        return $this;
    }
    
    /**
     * @ORM\PrePersist()
     */
    public function onPrePersist()
    {
        $this->guid = $this->guid ?? $this->generateId();
        $this->transaction = new Transaction('paypal', $this->getTotal());
        $this->transaction->setPurchase($this);
    }
    
    public function create(Basket $basket)
    {
        foreach ($basket->getProducts() as $product) {
            $this->addPurchasedItem(new PurchaseItem($product, $basket->getQuantity($product)));
        }
    }
    
    public function getTotal()
    {
        $total = 0.0;
        
        foreach ($this->getPurchasedItems() as $item) {
            $total += $item->getTotalPrice();
        }
        
        return $total;
    }

    public function getShippingAddress(): ?Address
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(?Address $shippingAddress): self
    {
        $this->shippingAddress = $shippingAddress;

        // set (or unset) the owning side of the relation if necessary
        $newPurchaseShipping = null === $shippingAddress ? null : $this;
        if ($shippingAddress->getPurchaseShipping() !== $newPurchaseShipping) {
            $shippingAddress->setPurchaseShipping($newPurchaseShipping);
        }

        return $this;
    }
}
