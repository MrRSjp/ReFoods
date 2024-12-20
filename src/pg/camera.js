

// ファイル選択のイベントリスナー
document.getElementById('fileInput').addEventListener('change', (event) => {
  const file = event.target.files[0]; // 選択されたファイル
  if (file) {
    const reader = new FileReader();
    reader.onload = (e) => {
      // 選択または撮影した画像をimgタグにセットして表示
      const img = document.getElementById('img');
      img.src = e.target.result; // img要素に画像データを設定
    };
    reader.readAsDataURL(file); // ファイルをData URLとして読み込む
  } else {
    console.error('ファイルが選択されていません');
  }
});
