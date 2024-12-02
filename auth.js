import { auth, provider } from './Firebase.js';

const signInWithGoogle = async () => {
  try {
    const result = await auth.signInWithPopup(provider);
    const user = result.user;
    console.log(user);
  } catch (error) {
    console.error(error);
  }
};

export { signInWithGoogle };