import React from 'react'
import ReactDOM from 'react-dom/client'
import TopStats from './topStats'
import MainGrid from './mainGrid'
import BottomGrid from './bottomGrid'

const MainContainer = () => {
    return (
        <div className='container'>
            <TopStats />
            <MainGrid />
            <BottomGrid />
        </div>
    )
}

export {MainContainer as default}