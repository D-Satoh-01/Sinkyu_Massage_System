// 複製先年月セレクトボックスの初期化と制御
document.addEventListener('DOMContentLoaded', function() {
  const duplicateDateSelect = document.getElementById('duplicate-date-select');
  
  // セレクトボックスが存在しない場合は処理を終了
  if (!duplicateDateSelect) return;

  const yearInput = document.getElementById('year');
  const monthInput = document.getElementById('month');

  // 各オプションの元のテキストを保存
  const originalTexts = new Map();
  Array.from(duplicateDateSelect.options).forEach(option => {
    originalTexts.set(option.value, option.textContent);
  });

  // 表示を更新する関数
  function updateDisplay() {
    const selectedValue = duplicateDateSelect.value;
    const [year, month] = selectedValue.split('-');
    
    // hidden inputを更新
    yearInput.value = year;
    monthInput.value = parseInt(month, 10);

    // 選択中のオプションのテキストを「YYYY年 MM月」形式に変更
    Array.from(duplicateDateSelect.options).forEach(option => {
      if (option.value === selectedValue) {
        option.textContent = `${year}年 ${month}月`;
      } else {
        // 他のオプションは元のテキストに戻す
        option.textContent = originalTexts.get(option.value);
      }
    });
  }

  // 初期表示を設定
  updateDisplay();

  // セレクトボックス変更時
  duplicateDateSelect.addEventListener('change', updateDisplay);

  // フォーカス時に元のテキストに戻す
  duplicateDateSelect.addEventListener('focus', function() {
    Array.from(duplicateDateSelect.options).forEach(option => {
      option.textContent = originalTexts.get(option.value);
    });
  });

  // ブラー時に選択中のテキストを再度変更
  duplicateDateSelect.addEventListener('blur', updateDisplay);
});
