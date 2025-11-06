//-- public/js/di-registration.js --//

// 医療機関選択時の新規登録フィールドの制御
function updateMedicalInstitutionFields() {
  const select = document.getElementById('medical_institutions_id');
  const newName = document.getElementById('new_medical_institution_name');

  if (select.value === '') {
    // 非選択の場合、入力フォームを有効化
    newName.readOnly = false;
    newName.classList.remove('readonly-field');
  } else {
    // 選択されている場合、入力フォームを無効化してクリア
    newName.readOnly = true;
    newName.classList.add('readonly-field');
    newName.value = '';
  }
}

// 新規医療機関名入力時の医療機関選択の制御
function clearMedicalInstitutionSelect() {
  const select = document.getElementById('medical_institutions_id');
  const newName = document.getElementById('new_medical_institution_name');

  if (newName.value.trim() !== '') {
    // 入力がある場合、選択を初期値（－－－）に戻す
    select.value = '';
  }
}

// 医師郵便番号から住所を検索する関数
async function searchDoctorAddress() {
  const postalCode = document.getElementById('postal_code').value;
  const address1El = document.getElementById('address_1');
  const address2El = document.getElementById('address_2');

  // 郵便番号を数字のみに変換
  const cleanPostalCode = postalCode.replace(/[^\d]/g, '');

  // 7桁の数字かチェック
  if (cleanPostalCode.length !== 7) {
    return; // エラーメッセージは表示せず、単に終了
  }

  try {
    // 郵便番号API呼び出し（zipcloud API使用）
    const response = await fetch(`https://zipcloud.ibsnet.co.jp/api/search?zipcode=${cleanPostalCode}`);
    const data = await response.json();

    if (data.status === 200 && data.results && data.results.length > 0) {
      const result = data.results[0];

      // 都道府県を設定
      address1El.value = result.address1;
      // 市区町村を設定
      address2El.value = result.address2 + result.address3;
    } else {
      // 該当する住所が無い場合は空欄
      address1El.value = '';
      address2El.value = '';
    }

  } catch (error) {
    console.error('Doctor address search error:', error);
  }
}

// ページ読み込み時に実行
document.addEventListener('DOMContentLoaded', function() {
  // 医師郵便番号入力時の処理
  const postalCodeInput = document.getElementById('postal_code');
  if (postalCodeInput) {
    postalCodeInput.addEventListener('input', function(e) {
      let value = this.value;

      // ハイフンを除去して数字のみに
      const numbersOnly = value.replace(/[^\d]/g, '');

      // 7桁になったら自動で住所検索を実行
      if (numbersOnly.length === 7) {
        // 表示用にハイフンを挿入（123-4567の形式）
        this.value = numbersOnly.substring(0, 3) + '-' + numbersOnly.substring(3);

        // 自動で住所検索を実行
        searchDoctorAddress();
      } else if (numbersOnly.length > 7) {
        // 7桁を超える場合は7桁でカット
        const truncated = numbersOnly.substring(0, 7);
        this.value = truncated.substring(0, 3) + '-' + truncated.substring(3);
        searchDoctorAddress();
      } else if (numbersOnly.length <= 3) {
        // 3桁以下の場合はそのまま
        this.value = numbersOnly;
      } else {
        // 4-6桁の場合はハイフンを挿入
        this.value = numbersOnly.substring(0, 3) + '-' + numbersOnly.substring(3);
      }
    });
  }
});
