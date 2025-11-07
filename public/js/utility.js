//-- public/js/utility.js --//

/**
 * 郵便番号から住所を検索する汎用関数
 * @param {string} postalCode - 郵便番号（ハイフンあり/なし両方対応）
 * @returns {Promise<Object|null>} 住所情報オブジェクトまたはnull
 *   - address1: 都道府県
 *   - address2: 市区町村
 *   - address3: 町域
 */
async function searchAddressByPostalCode(postalCode) {
  // 郵便番号を数字のみに変換
  const cleanPostalCode = postalCode.replace(/[^\d]/g, '');

  // 7桁の数字かチェック
  if (cleanPostalCode.length !== 7) {
    return null;
  }

  try {
    // 郵便番号API呼び出し（zipcloud API使用）
    const response = await fetch(`https://zipcloud.ibsnet.co.jp/api/search?zipcode=${cleanPostalCode}`);
    const data = await response.json();

    if (data.status === 200 && data.results && data.results.length > 0) {
      return data.results[0];
    } else {
      return null;
    }
  } catch (error) {
    console.error('Address search error:', error);
    return null;
  }
}

/**
 * 郵便番号を整形する関数（XXX-XXXX形式）
 * @param {string} value - 入力された郵便番号
 * @returns {string} 整形された郵便番号
 */
function formatPostalCode(value) {
  // ハイフンを除去して数字のみに
  const numbersOnly = value.replace(/[^\d]/g, '');

  // 桁数に応じて整形
  if (numbersOnly.length <= 3) {
    return numbersOnly;
  } else if (numbersOnly.length <= 7) {
    return numbersOnly.substring(0, 3) + '-' + numbersOnly.substring(3);
  } else {
    // 7桁を超える場合は7桁でカット
    const truncated = numbersOnly.substring(0, 7);
    return truncated.substring(0, 3) + '-' + truncated.substring(3);
  }
}

/**
 * 郵便番号から住所を検索してフィールドに設定する汎用関数
 * @param {string} postalCodeInputId - 郵便番号入力フィールドのID
 * @param {Object} addressConfig - 住所フィールドの設定
 *   - combined {boolean}: true=1つのフィールドに結合、false=2つに分割
 *   - address {string}: 結合時の住所フィールドID
 *   - address1 {string}: 分割時の都道府県フィールドID
 *   - address2 {string}: 分割時の市区町村フィールドID
 */
async function searchAndFillAddress(postalCodeInputId, addressConfig) {
  const postalCode = document.getElementById(postalCodeInputId).value;
  const result = await searchAddressByPostalCode(postalCode);

  if (result) {
    if (addressConfig.combined) {
      // 1つのフィールドに全住所を結合
      document.getElementById(addressConfig.address).value =
        result.address1 + result.address2 + result.address3;
    } else {
      // 2つのフィールドに分割
      document.getElementById(addressConfig.address1).value = result.address1;
      document.getElementById(addressConfig.address2).value = result.address2 + result.address3;
    }
  } else {
    // 該当する住所が無い場合は空欄
    if (addressConfig.combined) {
      document.getElementById(addressConfig.address).value = '';
    } else {
      document.getElementById(addressConfig.address1).value = '';
      document.getElementById(addressConfig.address2).value = '';
    }
  }
}

/**
 * 郵便番号入力フィールドに自動整形と住所検索を設定する汎用関数
 * @param {string} postalCodeInputId - 郵便番号入力フィールドのID
 * @param {Function} searchCallback - 7桁入力時に実行する住所検索関数
 */
function setupPostalCodeInput(postalCodeInputId, searchCallback) {
  const postalCodeInput = document.getElementById(postalCodeInputId);
  if (postalCodeInput) {
    postalCodeInput.addEventListener('input', function() {
      // 郵便番号を整形
      this.value = formatPostalCode(this.value);

      // ハイフンを除去して数字のみに
      const numbersOnly = this.value.replace(/[^\d]/g, '');

      // 7桁になったら自動で住所検索を実行
      if (numbersOnly.length === 7) {
        searchCallback();
      }
    });
  }
}
