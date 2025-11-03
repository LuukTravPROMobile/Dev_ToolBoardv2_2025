import React from 'react';
import "../../css/styles.scss";
import TicketTracker from './ticketTracker';
import DigitalOceanOverview from './digitalOceanOverview';

const BottomGrid = () => {
    return (
        <div className='bottom-grid'>
            <TicketTracker />
            <DigitalOceanOverview />
        </div>
    )
}

export {BottomGrid as default}