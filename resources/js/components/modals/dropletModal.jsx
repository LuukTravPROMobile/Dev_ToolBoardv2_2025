import React from "react";
import Modal from "react-modal";
import { Link } from "react-router-dom";
import "../../../css/styles.scss"; // keep this as you said it works

if (typeof document !== "undefined") {
  Modal.setAppElement("#root");
}

const DropletModal = ({ isOpen, onClose }) => (
  <Modal
    isOpen={isOpen}
    onRequestClose={onClose}
    contentLabel="Sentry Errors Modal"
    className="ReactModal__Content"
    overlayClassName="ReactModal__Overlay"
  >
    <div className="modal-content">
      <h3 className="modal__title">Droplet Status</h3>
      <p>Details about the droplet status can be seen here.</p>
      <Link to="/login" className="link-white">Login</Link>
      <button className="btn-close" onClick={onClose}>Close</button>
    </div>
  </Modal>
);

export {DropletModal as default};
