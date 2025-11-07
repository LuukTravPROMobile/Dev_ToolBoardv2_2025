import React, { useState } from "react";
import "../../css/styles.scss";
import GraduatesModal from "./modals/graduatesModal";
import DropletModal from "./modals/dropletModal";

const TopStats = () => {
  const [isGraduatesModalOpen, setIsGraduatesModalOpen] = useState(false);
  const [isDropletModalOpen, setIsDropletModalOpen] = useState(false);

  const scrollToErrorOverview = () => {
    // ✅ Make sure the ID matches the element in your DOM
    const element = document.getElementById("error-overview");
    if (!element) {
      console.warn("❗ No element with id='error-overview' found.");
      return;
    }

    // ✅ Get the navbar height (if it's fixed)
    const navbar = document.querySelector(".navbar");
    const navbarHeight = navbar ? navbar.offsetHeight : 0;

    // ✅ If your main content scrolls inside a container, target it
    const scrollContainer =
      document.querySelector(".main-container") || window;

    // Calculate where to scroll
    const elementPosition =
      element.getBoundingClientRect().top +
      (scrollContainer === window ? window.scrollY : scrollContainer.scrollTop);

    const offsetPosition = elementPosition - navbarHeight - 10;

    // ✅ Scroll smoothly
    if (scrollContainer === window) {
      window.scrollTo({
        top: offsetPosition,
        behavior: "smooth",
      });
    } else {
      scrollContainer.scrollTo({
        top: offsetPosition,
        behavior: "smooth",
      });
    }
  };

  const scrollToBottom = () => {
    window.scrollTo({
      top: document.body.scrollHeight,
      behavior: "smooth",
    });
  };

  return (
    <div className="top-stats">
      <button className="stat-card-btn" onClick={scrollToErrorOverview}>
        <div className="stat-label">Sentry Errors</div>
        <div className="stat-value">
          Today: <span style={{ marginLeft: "20px" }}>56</span>
        </div>
      </button>

      <button
        className="stat-card-btn"
        onClick={() => setIsGraduatesModalOpen(true)}
      >
        <div className="stat-label">Graduates</div>
        <div className="stat-value">Open Tickets</div>
      </button>

      <button className="stat-card-btn" onClick={scrollToBottom}>
        <div className="stat-label">Open Tickets</div>
        <div className="stat-value">Last Week</div>
      </button>

      <button
        className="stat-card-btn"
        onClick={() => setIsDropletModalOpen(true)}
      >
        <div className="stat-label">Droplet Status</div>
        <div className="stat-value">Active</div>
      </button>

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

export default TopStats;
