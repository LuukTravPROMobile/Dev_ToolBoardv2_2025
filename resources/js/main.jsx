import React from 'react'
import ReactDOM from 'react-dom/client'
import '../css/styles.scss'
import NavBar from './components/nav'
import MainContainer from './components/mainContainer'

function App() {
  return (
    <div>
        <NavBar />
        <MainContainer />
    </div>
  );
}

ReactDOM.createRoot(document.getElementById('root')).render(
  <React.StrictMode>
    <App />
  </React.StrictMode>
);
