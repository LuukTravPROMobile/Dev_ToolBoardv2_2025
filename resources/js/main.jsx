import React from 'react';
import ReactDOM from 'react-dom/client';
import '../css/styles.scss';
import { BrowserRouter, Routes, Route } from 'react-router-dom';

// Components
import NavBar from './components/nav';
import MainContainer from './components/mainContainer';
import NotFoundPage from './components/notFoundPage';
import LoginPage from './components/loginPage';
import RegisterPage from './components/registerPage';
import ProfilePage from './components/profilePage';

// Auth Context
import { AuthProvider } from './authContext';
function App() {
  return (
    <AuthProvider>
      <BrowserRouter>
        <NavBar />
        <Routes>
          <Route path="/" element={<MainContainer />} />
          <Route path="/login" element={<LoginPage />} />
          <Route path="/register" element={<RegisterPage />} />
          <Route path="/profile" element={<ProfilePage />} />
          <Route path="*" element={<NotFoundPage />} />
        </Routes>
      </BrowserRouter>
    </AuthProvider>
  );
}

ReactDOM.createRoot(document.getElementById('root')).render(
  <React.StrictMode>
    <App />
  </React.StrictMode>
);

export default App;
