import React from 'react';
import ReactDOM from 'react-dom/client';
import '../css/styles.scss';
import NavBar from './components/nav';
import MainContainer from './components/mainContainer';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import NotFoundPage from './components/notFoundPage';
import LoginPage from './components/loginPage';

function App() {
  return (
    <BrowserRouter>
      <NavBar />
      <Routes>
        <Route path="/" element={<MainContainer />} />
        <Route path="*" element={<NotFoundPage />} />
        <Route path="/login" element={<LoginPage />} />
      </Routes>
    </BrowserRouter>
  );
}

ReactDOM.createRoot(document.getElementById('root')).render(
  <React.StrictMode>
    <App />
  </React.StrictMode>
);

export {App as default}; 