import React from "react";
import "../../css/styles.scss";
import ErrorOverview from "./errorOverview";
import RecentErrors from "./recentErrors";
import ComparisonCard from "./comparisonCard";

const MainGrid = ({ errorOverviewRef }) => {
  return (
    <div>
      {/* Attach ref to ErrorOverview */}
      <div ref={errorOverviewRef}>
        <ErrorOverview />
      </div>

      <RecentErrors />
      <ComparisonCard />
    </div>
  );
};

export default MainGrid;
