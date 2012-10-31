<?php

/**
 * Outlines the interfaces and functionality for payment gateways. New payement
 * gateways should extend this class and implement all abstract methods to ensure
 * proper integration with WPEVT
 * 
 * @see DemoGateway
 * @since 1.4
 * @author Ben Lobaugh 
 */
abstract class WPEVT_PaymentGateway {
    
    /**
     * Human friendly string used for display purposes
     * 
     * @since 1.4
     * @author Ben Lobaugh
     * @var String 
     */
    private $mDisplayName;
    
    /**
     * Slug to use for reference within the code. All calls should be made
     * using this slug to identify the payment gateway
     * 
     * @since 1.4
     * @author Ben Lobaugh
     * @var type 
     */
    private $mSlug;
    
    /**
     * Sets up the payment gateway object with a basic set of information
     * 
     * @since 1.4
     * @author Ben Lobaugh
     * @param String $DisplayName
     * @param String $Slug 
     */
    public function __construct($DisplayName, $Slug) {
        $this->mDisplayName = $DisplayName;
        $this->mSlug = $Slug;
        
        if( isset( $_POST['wpevt_save_payment_gateway'] ) ) {
           $this->saveSettings();
        }
    }

        
    /**
     * Displays a form containing the required fields for the payment gateway
     * Should be implemented by the extending class
     * 
     * @since 1.4
     * @author Ben Lobaugh 
     */
    abstract public function settingsForm( $data );
    
    /**
     * Payment button to display to the user
     * 
     * All buttons should be named 'paymentButton'
     * 
     * @since 1.4
     * @author Ben Lobaugh 
     */
    abstract public function button();
    
    /**
     * This function handles all of the processing of all payments, from displaying
     * a payment form, to sending the user through the external payment gateway
     * to actually pay for the item.
     * 
     * After payment has been successfully processed redirect to the ticket page
     * so the user can complete registration. Set $_POST['paymentSuccessful'] 
     * to anything
     * 
     * @since 1.4
     * @author Ben Lobaugh 
     */
    abstract public function processPayment( $args );
    
    /**
     * If the system needs to process a return from the gateway it can be triggerd
     * by setting paymentReturn as a URL query param
     * 
     * paymentReturn should equal the saved ticket id
     * 
     * @param Integer $ticket_id
     */
    abstract public function processPaymentReturn( $ticket_id, $o );

        /**
     * Retrieves the human friendly payment gateway display name
     * 
     * @since 1.4
     * @author Ben Lobaugh
     * @return String 
     */
    public function displayName() {
        return $this->mDisplayName;
    }
    
    /**
     * Slug to use for reference within the code. All calls should be made
     * using this slug to identify the payment gateway
     * 
     * @since 1.4
     * @author Ben Lobaugh
     * @return String 
     */
    public function slug() {
        return $this->mSlug;
    }
    
    // All settings must be paymentGateway[$settingName] in the form
    /**
     * Automagically updates the WPEVT settings with the payment gateway info.
     * Payment gateway data is saved even when switching between payment gateways.
     * 
     * @since 1.4
     * @author Ben Lobaugh
     * @param Array $data 
     */
    public function saveSettings( $data ) {
        $o = get_option("eventTicketingSystem");
        
        $o['paymentGatewayData'][ $this->slug() ] = $data;
        
      //  echo '<pre>'; var_dump( $o ); echo '</pre>';
        
        update_option("eventTicketingSystem", $o);
        wp_redirect( admin_url( 'admin.php?page=ticketsettings&msg=paymentGateway') );
    }
    
    public function getSettings() {
        $o = get_option("eventTicketingSystem");
        
        return $o['paymentGatewayData'][ $this->slug() ];
    }
    
    
} // end class