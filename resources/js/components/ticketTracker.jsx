import React from 'react';
import "../../css/styles.scss";

const TicketTracker = () => {
    return (
        <div className="card">
            <h2 className="card-title">
                Ticket Tracker{" "}
                <span style={{ fontWeight: 400, fontSize: '0.9em' }}>(Monday.com)</span>
            </h2>
                
            <div className="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Team</th>
                            <th>Open</th>
                            <th>Closed</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Frontend</td>
                            <td>14</td>
                            <td>32</td>
                        </tr>
                        <tr>
                            <td>Backend</td>
                            <td>22</td>
                            <td>45</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    )
}

export { TicketTracker as default };
