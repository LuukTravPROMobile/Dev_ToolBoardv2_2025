import React, { useRef } from "react";
import TopStats from "./topStats";
import MainGrid from "./mainGrid";
import BottomGrid from "./bottomGrid";

const MainContainer = () => {
  // Ref for ErrorOverview inside MainGrid
  const errorOverviewRef = useRef(null);

  // Scroll function for TopStats
  const scrollToErrorOverview = () => {
    if (errorOverviewRef.current) {
      errorOverviewRef.current.scrollIntoView({
        behavior: "smooth",
        block: "start",
      });
    }
  };

  return (
    <div className="container">
      {/* Pass scroll function to TopStats */}
      <TopStats scrollToErrorOverview={scrollToErrorOverview} />

      {/* Pass ref to MainGrid */}
      <MainGrid errorOverviewRef={errorOverviewRef} />

      <BottomGrid />
    </div>
  );
};

export default MainContainer;
