import React from 'react'
import ReactDOM from 'react-dom/client'
import '../../css/styles.scss'
import ErrorOverview from './errorOverview'
import RecentErrors from './recentErrors'
import ComparisonCard from './comparisonCard'

const MainGrid = () => {
    return (
        <div>
            <ErrorOverview />
            <RecentErrors />
            <ComparisonCard />
        </div>
    )
}

export {MainGrid as default}