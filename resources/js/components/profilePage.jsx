import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import backgroundImage from '../../../images/loginBackground.jpg';
import profileImage from '../../../images/pfp.png';
import "../../css/styles.scss";

const ProfilePage = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const navigate = useNavigate();

  const handleSubmit = (e) => {
    e.preventDefault();
    console.log('Email:', email);
    console.log('Password:', password);
  };

  const goHome = () => navigate('/');

  return (
    <div
      className="page-container"
      style={{
        backgroundImage: `url(${backgroundImage})`,
        backgroundSize: "cover",
        backgroundPosition: "center",
        backgroundRepeat: "no-repeat",
        minHeight: "100vh",
        display: "flex",
        justifyContent: "center",
        alignItems: "center",
        paddingTop: "60px",
      }}
    >
        <div className='profile-container'>
            <img src={profileImage} alt="Profile" className="profile-image" />
            <div className='profile-info'>
                <h2 className="profile-username">John Doe</h2>
                <p className="profile-email">john@travpro.nl</p>
            </div>
        </div>
    </div>
  );
};

export {ProfilePage as default };
