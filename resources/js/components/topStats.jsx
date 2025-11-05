import React, { useState } from "react";
import "../../css/styles.scss";
import GraduatesModal from "./modals/graduatesModal";
import DropletModal from "./modals/dropletModal";

const TopStats = ({ scrollToErrorOverview }) => {
  const [isGraduatesModalOpen, setIsGraduatesModalOpen] = useState(false);
  const [isDropletModalOpen, setIsDropletModalOpen] = useState(false);

  const scrollToBottom = () => {
    window.scrollTo({
      top: document.body.scrollHeight,
      behavior: "smooth",
    });
  };

  return (
    <div className="top-stats">
      {/* Button to scroll to ErrorOverview in MainContainer */}
      <button className="stat-card-btn" onClick={scrollToErrorOverview}>
        <div className="stat-label">Sentry Errors</div>
        <div className="stat-value">
          Today: <span style={{ marginLeft: "20px" }}>56</span>
        </div>
      </button>

      {/* Button to open GraduatesModal */}
      <button
        className="stat-card-btn"
        onClick={() => setIsGraduatesModalOpen(true)}
      >
        <div className="stat-label">Graduates</div>
        <div className="stat-value">Open Tickets</div>
      </button>

      {/* Button to scroll to bottom of the page */}
      <button className="stat-card-btn" onClick={scrollToBottom}>
        <div className="stat-label">Open Tickets</div>
        <div className="stat-value">Last Week</div>
      </button>

      {/* Button to open DropletModal */}
      <button
        className="stat-card-btn"
        onClick={() => setIsDropletModalOpen(true)}
      >
        <div className="stat-label">Droplet Status</div>
        <div className="stat-value">Active</div>
      </button>

      {/* Modals */}
      <GraduatesModal
        isOpen={isGraduatesModalOpen}
        onClose={() => setIsGraduatesModalOpen(false)}
      />

      <DropletModal
        isOpen={isDropletModalOpen}
        onClose={() => setIsDropletModalOpen(false)}
      />
    </div>
  );
};

export { TopStats as default };
