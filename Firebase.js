// Firebase.js

// Firebaseモジュールをインポートする
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.5.0/firebase-app.js";
import { getAuth, GoogleAuthProvider } from "https://www.gstatic.com/firebasejs/10.5.0/firebase-auth.js";

// Firebaseの設定情報
const firebaseConfig = {
  apiKey: "AIzaSyC2qOcRheGYGvtWtQLWCLZmzKYqatuyS20",
  authDomain: "authentication2-cb1e0.firebaseapp.com",
  projectId: "authentication2-cb1e0",
  storageBucket: "authentication2-cb1e0.firebasestorage.app",
  messagingSenderId: "449563771119",
  appId: "1:449563771119:web:d6d41164844c7c8dc99b1a",
  measurementId: "G-9LLZSCFS0H"
};

// Firebaseの初期化
const app = initializeApp(firebaseConfig);

// Firebase Authenticationの初期化
const auth = getAuth(app);
const provider = new GoogleAuthProvider();

// Google認証を提供する
export { auth, provider };
